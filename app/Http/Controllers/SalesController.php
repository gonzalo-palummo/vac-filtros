<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\Repositories\RepositoryInterface;

class SalesController extends Controller
{

    public function index()
    {
        $sales = Sales::with(['products', 'sales_status', 'clients', 'payment_methods'])->get();
        return response()->json($sales);
    }

    public function last()
    {
        $lastSales = Sales::with(['products', 'sales_status', 'clients', 'payment_methods'])->where([['payment_date', '>=', Carbon::now()->subMonth()->toDateString()], ['payment_date', '<=', Carbon::now()]])->orderBy('payment_date', 'ASC')->get();
        return response()->json($lastSales);
    }

    public function next()
    {
        $nextSales = Sales::with(['products', 'sales_status', 'clients', 'payment_methods'])->where('payment_date', '>=', Carbon::now()->toDateString())->orderBy('payment_date', 'ASC')->get();
        return response()->json($nextSales);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        /* Example
            {
                "client_id": 1,
                "sale_status_id": 1,
                "payment_method_id": 1,
                "invoice_number": 6546,
                "invoice_date": "2020-06-10 11:11:11",
                "payment_date": "2020-10-10 11:11:11",
                "total_price": 4500,
                "products": [
                    {
                        "product_id": 1,
                        "amount": 10,
                        "unit_price": 4.88
                    },
                    {
                        "product_id": 2,
                        "amount": 20,
                        "unit_price": 18.45
                    },
                    {
                        "product_id": 3,
                        "amount": 30,
                        "unit_price": 18.45
                    }
                ]
            }
        */
        $data = $request->all();
        $data['status_changed_date'] = Carbon::now();

        $validation = Validator::make($request->all(), Sales::$rules, Sales::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        $stockDecrementation = []; $stockErrors = [];
        foreach ($request->input('products') as $productData) {
            $product = Products::find($productData['product_id']);
            /* Product
                {
                    "id": 1,
                    "category_id": 1,
                    "name": "ALFA LAVAL x 100",
                    "code": "58250",
                    "measurement": "13,5 X 25",
                    "mts": 3.38,
                    "stock": 200,
                    "price1": 4.63,
                    "price2": 4.88,
                    "price3": 6.04,
                    "created_at": null,
                    "updated_at": null
                }
            */
            if ($productData['amount'] <= $product['stock']) {
                $stockDecrementation[$productData['product_id']] = $productData;
            }
            else{
                array_push($stockErrors, "No hay stock suficiente de ".$product['name']);
            }
        }
        if(!empty($stockErrors)){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => $stockErrors]
            ], 422);
        }

        $last_sale = Sales::create($data);
        $sale = Sales::find($last_sale->id);

        foreach ($stockDecrementation as $productId => $product) {
            $sale->products()->attach($product['product_id'], [
                'amount' => $product['amount'],
                'unit_price' => $product['unit_price']
            ]);
            Products::find($productId)->decrement('stock', $product['amount']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has introducido la venta correctamente.',
            'data' => $data
        ]);
    }

    public function show(Sales $sales)
    {
        //
    }

    public function edit(Sales $sales)
    {
        //
    }

    public function update(Request $request, $id)
    {
        /* Example
            {
                "client_id": 1,
                "sale_status_id": 1,
                "payment_method_id": 1,
                "invoice_number": 6546,
                "invoice_date": "2020-06-10 11:11:11",
                "payment_date": "2020-10-10 11:11:11",
                "total_price": 4500,
                "products": [
                    {
                        "product_id": 1,
                        "amount": 20,
                        "unit_price": 4.88
                    },
                    {
                        "product_id": 2,
                        "amount": 10,
                        "unit_price": 18.45
                    },
                    {
                        "product_id": 4,
                        "amount": 30,
                        "unit_price": 18.45
                    }
                ]
            }
        */
        $data = $request->input();
        $sale = Sales::findOrFail($id);

        $validation = Validator::make($request->all(), Sales::$rules, Sales::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        if ($data['sale_status_id'] != $sale['sale_status_id']) {
            $data['status_changed_date'] = Carbon::now();
        }
        $existingData = [];
        $pivotData = DB::table('sales_products')->where('sale_id', '=', $sale->id)->get();
        /* Pivot data
            [
                {
                    "id": 9,
                    "sale_id": 13,
                    "product_id": 1,
                    "amount": 10,
                    "unit_price": 4.88,
                    "created_at": "2020-06-12 14:22:49",
                    "updated_at": "2020-06-12 14:22:49"
                },
                {
                    "id": 10,
                    "sale_id": 13,
                    "product_id": 2,
                    "amount": 20,
                    "unit_price": 18.45,
                    "created_at": "2020-06-12 14:22:49",
                    "updated_at": "2020-06-12 14:22:49"
                },
                {
                    "id": 11,
                    "sale_id": 13,
                    "product_id": 3,
                    "amount": 30,
                    "unit_price": 18.45,
                    "created_at": "2020-06-12 14:22:49",
                    "updated_at": "2020-06-12 14:22:49"
                }
            ]
        */
        foreach ($pivotData as $row) {
            $existingData[$row->product_id] = $row->amount;
        }

        $stockDiff = [];
        foreach ($data['products'] as $product) {
            /* Algorithm explained
                ED = Existing data                      10   20  30   0
                RD = Request data                       20   10   0  30
                SD = Stock difference (Increment)      -10   10  30 -30

                SD = ED - RD;

                RD = $product['amount']
                ED = (isset($existingData[$product['product_id']]) ? $existingData[$product['product_id']] : 0)
            */
            $stockDiff[$product['product_id']] = ((isset($existingData[$product['product_id']]) ? $existingData[$product['product_id']] : 0) - $product['amount']);
        }
        foreach (array_diff_key($existingData, $stockDiff) as $key => $value) {
            $stockDiff[$key] = ($value);
        }

        $products = Products::whereIn('id', array_keys($stockDiff))->get();
        $stockErrors = [];
        foreach ($products as $product) {
            if($product['stock'] + $stockDiff[$product['id']] < 0){
                array_push($stockErrors, "No hay stock suficiente de ".$product['name']);
            }
        }
        if(!empty($stockErrors)){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => $stockErrors]
            ], 422);
        }

        foreach ($stockDiff as $productId => $incremental) {
            Products::find($productId)->increment('stock', $incremental);
        }
        
        $sale->products()->detach();
        foreach ($request->input('products') as $product) {
            $sale->products()->attach($product['product_id'], [
                'amount' => $product['amount'],
                'unit_price' => $product['unit_price']
            ]);

        }
        $sale->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Has editado la venta correctamente.',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $sale = Sales::with('products')->where('id', $id)->get()->first();
        /* Sale
            {
                "id": 1,
                "client_id": 1,
                "sale_status_id": 2,
                "payment_method_id": 2,
                "invoice_number": 236,
                "invoice_date": "2010-06-02",
                "payment_date": "2010-11-03",
                "status_changed_date": "2022-02-04",
                "total_price": 5000,
                "created_at": null,
                "updated_at": null,
                "products": [
                    {
                        "id": 2,
                        "category_id": 2,
                        "name": "BOUMATIC x 100",
                        "code": "581000",
                        "measurement": "13,5 X 100",
                        "mts": 13.5,
                        "stock": 100,
                        "price1": 17.49,
                        "price2": 18.45,
                        "price3": 22.83,
                        "created_at": null,
                        "updated_at": "2020-06-10 15:41:31",
                        "pivot": {
                            "sale_id": 1,
                            "product_id": 2,
                            "amount": 10,
                            "unit_price": 500,
                            "created_at": null,
                            "updated_at": null
                        }
                    }
                ]
            }
        */
        foreach ($sale['products'] as $product) {
            Products::find($product['id'])->increment('stock', $product['pivot']['amount']);
        }
        $sale->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado la venta correctamente.'
        ]);
    }
}
