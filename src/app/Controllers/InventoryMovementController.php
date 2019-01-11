<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use CloudSoh\InventoryManagement\InventoryMovement;
use CloudSoh\InventoryManagement\Transformers\InventoryMovementTransformer;
use CloudSoh\InventoryManagement\Inventory;
use CloudSoh\InventoryManagement\Rules\Precision;

class InventoryMovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('snake');
    }

    private function validationRules($id = null)
    {
        return  [
            'quantity' => [
                'required',
                'numeric',
                'min:-999999999.999999',
                // new Precision(InventoryMovement::$precision),
                'max:999999999.999999',
            ],
            // 'cost' => 'required|numeric',
            'remarks' => 'required|string',
        ];
    }

    public function index()
    {
        return responder()->success(InventoryMovement::all(), InventoryMovementTransformer::class)->respond(Response::HTTP_OK);
    }

    public function store(Request $request, Inventory $inventory)
    {
        //POST api/inventory-movements (store)
        $data = $this->validate($request, $this->validationRules());

        $movement = auth()->user()->createMovement($inventory, $data['quantity'], $data['remarks']);

        return responder()->success($movement, InventoryMovementTransformer::class)->respond(Response::HTTP_CREATED);
    }

    public function show(Request $request, Inventory $inventory)
    {
        $data = $this->validate($request, [
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|required_with:start_time',
        ]);

        $movements = $inventory->movements();

        if ($request->input('start_time')) {
            \DB::beginTransaction();

            $endQuantity = (clone $movements)->where('created_at', '<=', $data['end_time'])->sum('quantity');
            $movement = $inventory->createMovement($endQuantity, "Stock balance at {$data['end_time']}");
            $movement->created_at = $data['end_time'];
            $movement->save();

            $startQuantity = (clone $movements)->where('created_at', '<=', $data['start_time'])->sum('quantity');
            $movement = $inventory->createMovement($startQuantity, "Stock balance at {$data['start_time']}");
            $movement->created_at = $data['start_time'];
            $movement->save();

            $movements->whereBetween('created_at', [
                $data['start_time'],
                $data['end_time'],
            ]);
        }

        $paginated = $movements->paginate($request->input('per_page', 10));

        \DB::rollBack();
        return responder()->success(
            $paginated,
            InventoryMovementTransformer::class
        )->respond(Response::HTTP_OK);
    }

    public function update(Request $request, InventoryMovement $inventoryMovement)
    {
        $data = $this->validate($request, $this->validationRules($inventoryMovement->id, $inventoryMovement->sku_code));

        $inventoryMovement->update($data);

        return responder()->success($inventoryMovement, InventoryMovementTransformer::class)->respond(Response::HTTP_OK);
    }

    public function destroy(Request $request, InventoryMovement $inventoryMovement)
    {
        $inventoryMovement->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }
}
