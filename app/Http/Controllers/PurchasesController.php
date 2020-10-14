<?php

namespace App\Http\Controllers;

use App\Models\Purchases;
use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchases::with(['providers', 'supplies', 'purchases_status', 'payment_methods'])->get();
        return response()->json($purchases);
    }

    public function last()
    {
        $lastPurchases = Purchases::with(['providers', 'supplies', 'purchases_status', 'payment_methods'])->where([['payment_date', '>=', Carbon::now()->subMonth()->toDateString()], ['payment_date', '<=', Carbon::now()]])->orderBy('payment_date', 'ASC')->get();
        return response()->json($lastPurchases);
    }

    public function next()
    {
        $nextPurchases = Purchases::with(['providers', 'supplies', 'purchases_status', 'payment_methods'])->where('payment_date', '>=', Carbon::now()->toDateString())->orderBy('payment_date', 'ASC')->get();
        return response()->json($nextPurchases);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        /* Example
            {
                "provider_id": 1,
                "purchase_status_id": 1,
                "payment_method_id": 1,
                "invoice_number": 56454,
                "price": 7500,
                "invoice_date": "2020-06-10 11:11:11",
                "payment_date": "2020-10-10 11:11:11",
                "supplies": [
                    {
                        "supplie_id": 1,
                        "amount": 10,
                        "unit_price": 10
                    },
                    {
                        "supplie_id": 2,
                        "amount": 20,
                        "unit_price": 20
                    },
                    {
                        "supplie_id": 3,
                        "amount": 30,
                        "unit_price": 30
                    }
                ]
            }
        */
        $data = $request->all();
        $data['status_changed_date'] = Carbon::now();
        $validation = Validator::make($data, Purchases::$rules, Purchases::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        $last_purchase = Purchases::create($data);
        $purchase = Purchases::find($last_purchase->id);

        foreach ($request->input('supplies') as $supplie) {
            $purchase->supplies()->attach($supplie['supplie_id'], [
                'amount' => $supplie['amount'],
                'unit_price' => $supplie['unit_price']
            ]);
            Supplies::find($supplie['supplie_id'])->increment('stock', $supplie['amount']);
            // $supply = Supplies::find($supplie['supplie_id']);
            // $supply->increment('stock', $supplie['amount']);
            // $supply->update(['last_price' => $supplie['unit_price']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has introducido la compra correctamente.',
            'data' => $data
        ]);
    }

    public function show(Purchases $purchases)
    {
        //
    }

    public function edit(Purchases $purchases, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = $request->input();
        $purchase = Purchases::findOrFail($id);
        $validation = Validator::make($data, Purchases::$rules, Purchases::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        if ($data['purchase_status_id'] != $purchase['purchase_status_id']) {
            $data['status_changed_date'] = Carbon::now();
        }
        /* Walkthrough:
            ED = Existing data; RD = Request data;

            - Retrieve ED
            - Compare ED to RD for stock management
                Posible scenarios:
                    a) ED not in RD => Supplies::find(ED.id)->decrement('stock', ED.amount);
                    b) RD not in ED => Supplies::find(RD.id)->increment('stock', RD.amount);
                    c) In both => Supplies::find(RD.id)->increment('stock', (RD.amount - ED.amount));
                        (It could be always like:
                            Supplies::find(id)->increment('stock', (RD.amount - ED.amount));
                        if when not in the other one equals 0)
                if (notEnoughStock) => return 422 with validation error
                else => update supplies stock
            - Erase pivot data
            - Insert pivot data
        */
        $existingData = [];
        $pivotData = DB::table('purchases_supplies')->where('purchase_id', '=', $purchase->id)->get();
        foreach ($pivotData as $row) {
            $existingData[$row->supplie_id] = $row->amount;
        }
        $stockDiff = [];
        foreach ($data['supplies'] as $supply) {
            /* Algorithm explained
                RD = Request data
                ED = Existing data
                SD = Stock difference (Incremental)

                SD = RD - ED;

                RD = $supply['amount']
                ED = (isset($existingData[$supply['supplie_id']]) ? $existingData[$supply['supplie_id']] : 0)
            */
            $stockDiff[$supply['supplie_id']] = ($supply['amount'] - (isset($existingData[$supply['supplie_id']]) ? $existingData[$supply['supplie_id']] : 0));
        }
        foreach (array_diff_key($existingData, $stockDiff) as $key => $value) {
            $stockDiff[$key] = ($value * -1);
        }
        
        $supplies = Supplies::whereIn('id', array_keys($stockDiff))->get();
        $stockErrors = [];
        foreach ($supplies as $supply) {
            if($supply['stock'] + $stockDiff[$supply['id']] < 0){
                array_push($stockErrors, "Al editar esto quedará stock negativo de ".$supply['name']);
            }
        }
        if(!empty($stockErrors)){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => $stockErrors]
            ], 422);
        }

        foreach ($stockDiff as $supplyId => $incremental) {
            Supplies::find($supplyId)->increment('stock', $incremental);
        }

        $purchase->supplies()->detach();

        foreach ($request->input('supplies') as $supplie) {
            $purchase->supplies()->attach(
                $supplie['supplie_id'],
                [
                    'amount' => $supplie['amount'],
                    'unit_price' => $supplie['unit_price']
                ]
            );
        }

        $purchase->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Has editado la compra correctamente.',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $purchase = Purchases::with('supplies')->where('id', $id)->get()->first();
        foreach ($purchase['supplies'] as $supply) {
            Supplies::find($supply['id'])->decrement('stock', $supply['pivot']['amount']);
        }
        $purchase->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado la compra correctamente.'
        ]);
    }
}
