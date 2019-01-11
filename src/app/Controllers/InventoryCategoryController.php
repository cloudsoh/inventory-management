<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use CloudSoh\InventoryManagement\InventoryCategory;
use CloudSoh\InventoryManagement\Transformers\InventoryCategoryTransformer;
use CloudSoh\InventoryManagement\Transformers\InventoryTransformer;

class InventoryCategoryController extends Controller
{
    private function validationRules($id = null)
    {
        return [
            'name' => "required|string|unique:inventory_categories,name,$id,id,deleted_at,NULL",
        ];
    }

    public function index()
    {
        return responder()->success(InventoryCategory::all(), InventoryCategoryTransformer::class)->respond(200);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->validationRules());

        return responder()->success(InventoryCategory::create($data), InventoryCategoryTransformer::class)->respond(201);
    }

    public function show(Request $request, InventoryCategory $inventoryCategory)
    {
        return responder()->success($inventoryCategory, InventoryCategoryTransformer::class)->respond(200);
    }

    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $data = $this->validate($request, $this->validationRules($inventoryCategory->id));

        $inventoryCategory->update($data);

        return responder()->success($inventoryCategory, InventoryCategoryTransformer::class)->respond(200);
    }

    public function destroy(Request $request, InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }

    public function getInventories(Request $request, InventoryCategory $inventoryCategory)
    {
        return responder()->success(
            $inventoryCategory->inventories,
            InventoryTransformer::class
        )->respond(Response::HTTP_OK);
    }
}
