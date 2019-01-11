<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use CloudSoh\InventoryManagement\InventoryMetric;
use CloudSoh\InventoryManagement\Transformers\InventoryMetricTransformer;

class InventoryMetricController extends Controller
{
    private function validationRules($id = null)
    {
        return [
            'name' => "required|string|unique:inventory_metrics,name,$id,id,deleted_at,NULL",
            'quantity' => 'required|numeric',
            'metric_id' => 'required|exists:metrics,id',
            'inventory_id' => 'required|exists:inventories,id',
        ];
    }

    public function index()
    {
        return responder()->success(InventoryMetric::all(), InventoryMetricTransformer::class)->respond(200);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->validationRules());

        return responder()->success(InventoryMetric::create($data), InventoryMetricTransformer::class)->respond(201);
    }

    public function show(Request $request, InventoryMetric $inventoryMetric)
    {
        return responder()->success($inventoryMetric, InventoryMetricTransformer::class)->respond(200);
    }

    public function update(Request $request, InventoryMetric $inventoryMetric)
    {
        $data = $this->validate($request, $this->validationRules($inventoryMetric->id));

        $inventoryMetric->update($data);

        return responder()->success($inventoryMetric, InventoryMetricTransformer::class)->respond(200);
    }

    public function destroy(Request $request, InventoryMetric $inventoryMetric)
    {
        $inventoryMetric->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }
}
