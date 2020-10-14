<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Products;
use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with(['supplies', 'products'])->get();
        return response()->json($productions);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        /* Request example
            {
                "product_id": 7,
                "amount": "2",
                "supplies": [
                    {
                        "supply_id": 1,
                        "wastes": "10"
                    },
                    {
                        "supply_id": 2,
                        "wastes": "10"
                    },
                    {
                        "supply_id": 3,
                        "wastes": "10"
                    },
                    {
                        "supply_id": 4,
                        "wastes": "10"
                    }
                ]
            }
        */
        $data = $request->all();
        $validation = Validator::make($data, Production::$rules, Production::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }

        $stockDecrementation = []; $stockErrors = [];
        $product = Products::with('supplies')->where('id', $request->input('product_id'))->get()->first();
        
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
                "updated_at": null,
                "supplies": [
                    {
                        "id": 1,
                        "measure_id": 1,
                        "code": "123",
                        "name": "Hilo",
                        "stock": 30,
                        "last_price": 10,
                        "created_at": null,
                        "updated_at": null,
                        "last_purchase_date": {
                            "id": 1,
                            "purchase_id": 1,
                            "supplie_id": 1,
                            "amount": 1000,
                            "unit_price": 10,
                            "created_at": "2020-06-08 18:44:41",
                            "updated_at": null
                        },
                        "pivot": {
                            "product_id": 1,
                            "supplie_id": 1,
                            "supplie_amount": 10,
                            "created_at": null,
                            "updated_at": null
                        }
                    }
                ]
            }
        */
        foreach ($request->input('supplies') as $supply) {
            foreach ($product['supplies'] as $productSupply) { // Upgradeable
                if($productSupply['id'] == $supply['supply_id']){
                    /* Algorithm explained
                        S = Lo que se consume para fabricar el producto;
                        C = Cantidad de productos producidos;
                        W = Total supply waste;
        
                        (S * C) + W
        
                        S = $productSupply['pivot']['supplie_amount']
                        C = $request->input('amount')
                        W = $supplie['wastes']
                    */
                    $demandedStock = ($productSupply['pivot']['supplie_amount'] * $request->input('amount')) + $supply['wastes'];
                    if($demandedStock <= $productSupply['stock']){
                        $stockDecrementation[$supply['supply_id']] = $demandedStock;
                    }
                    else{
                        array_push($stockErrors, "No hay stock suficiente de ".$productSupply['name']);
                    }
                }
            }
        }
        if(!empty($stockErrors)){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => $stockErrors]
            ], 422);
        }

        foreach ($stockDecrementation as $supplyId => $decrementation) {
            Supplies::find($supplyId)->decrement('stock', $decrementation);
        }
        $product->increment('stock', $request->input('amount'));

        $last_production = Production::create($data);
        $production = Production::find($last_production->id);

        foreach ($request->input('supplies') as $supplie) {
            $production->supplies()->attach($supplie['supply_id'], ['waste' => $supplie['wastes']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has introducido la producción correctamente.',
            'data' => $data
        ]);
    }


    public function show(Production $production)
    {
        //
    }


    public function edit(Production $production)
    {
        //
    }

    public function update(Request $request, $id)
    {
        /* Example
            {
                "product_id": 7,
                "amount": "2",
                "supplies": [
                    {
                        "supply_id": 1,
                        "wastes": "20"
                    },
                    {
                        "supply_id": 2,
                        "wastes": "5"
                    },
                    {
                        "supply_id": 3,
                        "wastes": "10"
                    },
                    {
                        "supply_id": 4,
                        "wastes": "10"
                    }
                ]
            }
        */
        $data = $request->input();
        $production = Production::findOrFail($id);
        $production = Production::with(['products.supplies', 'supplies'])->where('id', $production['id'])->get()->first();
        $validation = Validator::make($data, Production::$rules, Production::$errorMessages);
        if ($validation->fails()) {
            $errors = $validation->errors();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => $errors
            ], 422);
        }
        /* Production
            {
                "id": 5,
                "product_id": 7,
                "amount": 2,
                "created_at": "2020-06-12 19:36:20",
                "updated_at": "2020-06-12 19:36:20",
                "products": {
                    "id": 7,
                    "category_id": 2,
                    "name": "Test3",
                    "code": "654654652",
                    "measurement": "12x25",
                    "mts": 540,
                    "stock": 102,
                    "price1": 12,
                    "price2": 24,
                    "price3": 38,
                    "created_at": "2020-06-12 19:34:47",
                    "updated_at": "2020-06-12 19:36:20",
                    "supplies": [
                        {
                            "id": 1,
                            "measure_id": 1,
                            "code": "123",
                            "name": "Hilo",
                            "stock": 9980,
                            "last_price": 10,
                            "created_at": null,
                            "updated_at": "2020-06-12 19:36:20",
                            "last_purchase_date": {
                                "id": 16,
                                "purchase_id": 2,
                                "supplie_id": 1,
                                "amount": 50,
                                "unit_price": 10,
                                "created_at": "2020-06-10 20:17:49",
                                "updated_at": "2020-06-10 20:17:49"
                            },
                            "pivot": {
                                "product_id": 7,
                                "supplie_id": 1,
                                "supplie_amount": 15,
                                "created_at": "2020-06-12 19:34:48",
                                "updated_at": "2020-06-12 19:34:48"
                            }
                        },
                        {
                            "id": 2,
                            "measure_id": 3,
                            "code": "456",
                            "name": "Bolsa",
                            "stock": 416,
                            "last_price": null,
                            "created_at": null,
                            "updated_at": "2020-06-12 19:36:20",
                            "last_purchase_date": {
                                "id": 17,
                                "purchase_id": 2,
                                "supplie_id": 2,
                                "amount": 10,
                                "unit_price": 20,
                                "created_at": "2020-06-10 20:17:50",
                                "updated_at": "2020-06-10 20:17:50"
                            },
                            "pivot": {
                                "product_id": 7,
                                "supplie_id": 2,
                                "supplie_amount": 12,
                                "created_at": "2020-06-12 19:34:48",
                                "updated_at": "2020-06-12 19:34:48"
                            }
                        },
                        {
                            "id": 3,
                            "measure_id": 2,
                            "code": "567",
                            "name": "Tela",
                            "stock": 910,
                            "last_price": 15,
                            "created_at": null,
                            "updated_at": "2020-06-12 19:36:20",
                            "last_purchase_date": {
                                "id": 18,
                                "purchase_id": 2,
                                "supplie_id": 3,
                                "amount": 30,
                                "unit_price": 30,
                                "created_at": "2020-06-10 20:17:50",
                                "updated_at": "2020-06-10 20:17:50"
                            },
                            "pivot": {
                                "product_id": 7,
                                "supplie_id": 3,
                                "supplie_amount": 20,
                                "created_at": "2020-06-12 19:34:48",
                                "updated_at": "2020-06-12 19:34:48"
                            }
                        },
                        {
                            "id": 4,
                            "measure_id": 5,
                            "code": "895",
                            "name": "Test",
                            "stock": 770,
                            "last_price": null,
                            "created_at": null,
                            "updated_at": "2020-06-12 19:36:20",
                            "last_purchase_date": null,
                            "pivot": {
                                "product_id": 7,
                                "supplie_id": 4,
                                "supplie_amount": 50,
                                "created_at": "2020-06-12 19:34:48",
                                "updated_at": "2020-06-12 19:34:48"
                            }
                        }
                    ]
                },
                "supplies": [
                    {
                        "id": 1,
                        "measure_id": 1,
                        "code": "123",
                        "name": "Hilo",
                        "stock": 9980,
                        "last_price": 10,
                        "created_at": null,
                        "updated_at": "2020-06-12 19:36:20",
                        "last_purchase_date": {
                            "id": 16,
                            "purchase_id": 2,
                            "supplie_id": 1,
                            "amount": 50,
                            "unit_price": 10,
                            "created_at": "2020-06-10 20:17:49",
                            "updated_at": "2020-06-10 20:17:49"
                        },
                        "pivot": {
                            "production_id": 5,
                            "supplie_id": 1,
                            "waste": "10.00",
                            "created_at": "2020-06-12 19:36:20",
                            "updated_at": "2020-06-12 19:36:20"
                        }
                    },
                    {
                        "id": 2,
                        "measure_id": 3,
                        "code": "456",
                        "name": "Bolsa",
                        "stock": 416,
                        "last_price": null,
                        "created_at": null,
                        "updated_at": "2020-06-12 19:36:20",
                        "last_purchase_date": {
                            "id": 17,
                            "purchase_id": 2,
                            "supplie_id": 2,
                            "amount": 10,
                            "unit_price": 20,
                            "created_at": "2020-06-10 20:17:50",
                            "updated_at": "2020-06-10 20:17:50"
                        },
                        "pivot": {
                            "production_id": 5,
                            "supplie_id": 2,
                            "waste": "10.00",
                            "created_at": "2020-06-12 19:36:20",
                            "updated_at": "2020-06-12 19:36:20"
                        }
                    },
                    {
                        "id": 3,
                        "measure_id": 2,
                        "code": "567",
                        "name": "Tela",
                        "stock": 910,
                        "last_price": 15,
                        "created_at": null,
                        "updated_at": "2020-06-12 19:36:20",
                        "last_purchase_date": {
                            "id": 18,
                            "purchase_id": 2,
                            "supplie_id": 3,
                            "amount": 30,
                            "unit_price": 30,
                            "created_at": "2020-06-10 20:17:50",
                            "updated_at": "2020-06-10 20:17:50"
                        },
                        "pivot": {
                            "production_id": 5,
                            "supplie_id": 3,
                            "waste": "10.00",
                            "created_at": "2020-06-12 19:36:20",
                            "updated_at": "2020-06-12 19:36:20"
                        }
                    },
                    {
                        "id": 4,
                        "measure_id": 5,
                        "code": "895",
                        "name": "Test",
                        "stock": 770,
                        "last_price": null,
                        "created_at": null,
                        "updated_at": "2020-06-12 19:36:20",
                        "last_purchase_date": null,
                        "pivot": {
                            "production_id": 5,
                            "supplie_id": 4,
                            "waste": "10.00",
                            "created_at": "2020-06-12 19:36:20",
                            "updated_at": "2020-06-12 19:36:20"
                        }
                    }
                ]
            }
        */
        /* Instructions
            - Get production stock reduction (products and supplies)
                - Products:
                    $production['amount']
                - Supplies:
                    - foreach $production['products']['supplies'] as $supply:
                        ($supply['pivot']['supplie_amount'] * $production['amount']) + (TOTAL SUPPLY WASTE)
            - Get request stock reduction
            - Get difference of stock
            - Validate if change is possible:
                - if not => return 422 with stock validation
                - if is possible => modify stock
        */
        if($production['product_id'] != $data['product_id']){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['product_id' => ["No se puede modificar el producto de una fabricación."]]
            ], 422);
        }
        if(($production['products']['stock'] - $production['amount'] + $data['amount']) < 0){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => ["No hay stock suficiente de ".$production['products']['name']]]
            ], 422);
        }
        
        $stockRequest = []; $stockConsumption = []; $demandedStock = []; $stockErrors = [];
        foreach ($data['supplies'] as $supply) {
            foreach ($production['products']['supplies'] as $productSupply) { // Upgradeable
                if($productSupply['id'] == $supply['supply_id']){
                    /* Algorithm explained
                        S = Lo que se consume para fabricar el producto;
                        C = Cantidad de productos producidos;
                        W = Total supply waste;
        
                        (S * C) + W
        
                        S = $productSupply['pivot']['supplie_amount']
                        C = $data['amount']
                        W = $supplie['wastes']
                    */
                    $stockRequest[$supply['supply_id']] = ($productSupply['pivot']['supplie_amount'] * $data['amount']) + $supply['wastes'];
                }
                $stockConsumption[$productSupply['id']] = ($productSupply['pivot']['supplie_amount'] * $production['amount']);
            }
        }
        foreach ($production['supplies'] as $supply){
            $stockConsumption[$supply['id']] += $supply['pivot']['waste'];

            if($supply['stock'] + ($stockConsumption[$supply['id']] - $stockRequest[$supply['id']]) >= 0){
                $demandedStock[$supply['id']] = ($stockConsumption[$supply['id']] - $stockRequest[$supply['id']]);
            }
            else{
                array_push($stockErrors, "No hay stock suficiente de ".$supply['name']);
            }
        }
        if(!empty($stockErrors)){
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'data' => ['stock' => $stockErrors]
            ], 422);
        }
        // return response()->json([
        //     'suppliesIncrementals' => $demandedStock,
        //     'productIncremental' => ($production['amount'] - $data['amount'])
        // ]);
        
        // DEBUGGING
        foreach ($demandedStock as $supplyId => $incremental) {
            Supplies::find($supplyId)->increment('stock', $incremental);
        }
        Products::find($production['product_id'])->increment('stock', ($data['amount'] - $production['amount']));
        $production->update($data);

        foreach ($request->input('supplies') as $supplie){
            $production->supplies()->updateExistingPivot($supplie['supply_id'], ['waste' => $supplie['wastes']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Has editado la producción correctamente.',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $production = Production::with('supplies')->where('id', $id)->get()->first();
        /* Production
            {
                "id": 1,
                "product_id": 1,
                "amount": 30,
                "created_at": null,
                "updated_at": null,
                "supplies": [
                    {
                        "id": 1,
                        "measure_id": 1,
                        "code": "123",
                        "name": "Hilo",
                        "stock": 9900,
                        "last_price": 10,
                        "created_at": null,
                        "updated_at": "2020-06-09 18:18:51",
                        "last_purchase_date": {
                            "id": 1,
                            "purchase_id": 1,
                            "supplie_id": 1,
                            "amount": 1000,
                            "unit_price": 10,
                            "created_at": "2020-06-09 13:16:40",
                            "updated_at": null
                        },
                        "pivot": {
                            "production_id": 1,
                            "supplie_id": 1,
                            "waste": "20.00",
                            "created_at": null,
                            "updated_at": null
                        }
                    }
                ]
            }
        */

        $product = Products::with('supplies')->where('id', $production['product_id'])->get()->first();
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
                "updated_at": null,
                "supplies": [
                    {
                        "id": 1,
                        "measure_id": 1,
                        "code": "123",
                        "name": "Hilo",
                        "stock": 30,
                        "last_price": 10,
                        "created_at": null,
                        "updated_at": null,
                        "last_purchase_date": {
                            "id": 1,
                            "purchase_id": 1,
                            "supplie_id": 1,
                            "amount": 1000,
                            "unit_price": 10,
                            "created_at": "2020-06-08 18:44:41",
                            "updated_at": null
                        },
                        "pivot": {
                            "product_id": 1,
                            "supplie_id": 1,
                            "supplie_amount": 10,
                            "created_at": null,
                            "updated_at": null
                        }
                    }
                ]
            }
        */

        $increments = [];
        foreach ($production['supplies'] as $productionSupply) {
            foreach ($product['supplies'] as $productSupply) { // Upgradeable
                if($productSupply['id'] == $productionSupply['id']){
                    /* Algorithm explained
                        S = Lo que se consume para fabricar el producto;
                        C = Cantidad de productos producidos;
                        W = Total supply waste;
        
                        (S * C) + W
        
                        S = $productSupply['pivot']['supplie_amount']
                        C = $production['amount']
                        W = $productionSupply['pivot']['waste']
                    */
                    $increments[$productionSupply['id']] = ($productSupply['pivot']['supplie_amount'] * $production['amount']) + $productionSupply['pivot']['waste'];
                }
            }
        }

        foreach ($increments as $supplyId => $increment) {
            Supplies::find($supplyId)->increment('stock', $increment);
        }
        $product->decrement('stock', $production['amount']);
        $production->delete();
        return response()->json([
            'success' => true,
            'message' => 'Has borrado la producción correctamente.'
        ]);
    }

    // private function calculateStock(){
    //     $response = [
    //         "" => [],
    //         "" => [],
    //         "" => [],
    //     ];
    //     return $response;
    // }
}
