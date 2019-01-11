<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use CloudSoh\InventoryManagement\Inventory;
use CloudSoh\InventoryManagement\Transformers\InventoryTransformer;
use CloudSoh\InventoryManagement\Transformers\InventoryProductTransformer;
use CloudSoh\InventoryManagement\InventoryMovement;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('snake');
    }

    private function validationRules($id = null)
    {
        return  [
            'name' => "required|string|unique:inventories,name,$id,id,deleted_at,NULL",
            'sku' => "required|string|unique:inventories,sku,$id,id,deleted_at,NULL",
            'inventory_category_id' => 'required|string',
            'metric_id' => 'required|string|exists:metrics,id',
            'description' => 'nullable|string',
        ];
    }

    public function index()
    {
        return responder()->success(Inventory::all(), InventoryTransformer::class)->with('category', 'metric')->respond(200);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->validationRules());

        $inventory = Inventory::create($data);

        return responder()->success($inventory, InventoryTransformer::class)->with('category', 'metric')->respond(201);
    }

    public function show(Request $request, Inventory $inventory)
    {
        return responder()
            ->success($inventory, InventoryTransformer::class)
            ->with('category', 'metric')
            ->respond(200);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $this->validate($request, $this->validationRules($inventory->id, $inventory->sku_code));

        $inventory->update($data);

        return responder()
            ->success($inventory, InventoryTransformer::class)
            ->with('category', 'metric')
            ->respond(200);
    }

    public function destroy(Request $request, Inventory $inventory)
    {
        $inventory->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }

    public function getProducts(Request $request, Inventory $inventory)
    {
        return responder()
            ->success($inventory->inventoryProducts, InventoryProductTransformer::class)
            ->with('inventory', 'product', 'measurement')
            ->respond(Response::HTTP_OK);
    }

    public function chart(Request $request)
    {
        // a = inventory_id
        $data = $this->validate($request, [
            'inventory_id' => 'required|array|between:1,5',
            'inventory_id.*' => 'exists:inventories,id',
            'time_format' => [
                Rule::in(['day', 'month', 'year']),
            ],
            'type' => [
                Rule::in(['in', 'out', 'both']),
            ],
        ]);

        switch ($data['type']) {
            case 'in':
                $constraint = '> 0';
                break;
            case 'out':
                $constraint = '< 0';
                break;
            default:
                $constraint = 'IS NOT NULL';
        }
        $result = InventoryMovement::
            join('inventories', 'inventories.id', '=', 'inventory_movements.inventory_id')
            ->join('metrics', 'metrics.id', '=', 'inventories.metric_id')
            ->selectRaw("
                inventories.name, 
                CAST(SUM(inventory_movements.quantity) AS CHAR)+0 as total,
                metrics.symbol as symbol,
                {$data['time_format']}(inventory_movements.created_at) as {$data['time_format']}
            ")
            ->whereIn('inventories.id', $data['inventory_id'])
            ->whereRaw("inventory_movements.quantity $constraint")
            ->groupBy(\DB::raw("{$data['time_format']}(inventory_movements.created_at)"), 'inventory_movements.inventory_id')
            ->get();

        // return $result;
        return responder()->success([
            // 'columns' =>
            'rows' => $result->groupBy($data['time_format']),
        ])->respond(Response::HTTP_OK);
    }
}
