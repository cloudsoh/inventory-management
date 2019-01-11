<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use CloudSoh\InventoryManagement\Measurement;
use CloudSoh\InventoryManagement\Transformers\MeasurementTransformer;
use CloudSoh\InventoryManagement\Metric;
use CloudSoh\InventoryManagement\Rules\Precision;

class MeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('snake');
    }

    private function validationRules($id = null)
    {
        return [
            'name' => "required|string|unique:measurements,name,$id,id,deleted_at,NULL",
            'quantity' => [
                'bail', 
                'required', 
                'numeric', 
                'min:0.001', 
                new Precision(Measurement::$precision), 'max:999999999.999'
            ],
        ];
    }

    public function index()
    {
        return responder()->success(Measurement::all(), MeasurementTransformer::class)->respond(Response::HTTP_OK);
    }

    public function store(Request $request, Metric $metric)
    {
        $data = $this->validate($request, $this->validationRules());
        return responder()->success(
            $metric->measurements()->create($data),
            MeasurementTransformer::class
        )->respond(Response::HTTP_CREATED);
    }

    public function show(Request $request, Measurement $measurement)
    {
        return responder()->success($measurement, MeasurementTransformer::class)->respond(Response::HTTP_OK);
    }

    public function update(Request $request, Measurement $measurement)
    {
        $data = $this->validate($request, $this->validationRules($measurement->id));

        $measurement->update($data);

        return responder()->success($measurement, MeasurementTransformer::class)->respond(Response::HTTP_OK);
    }

    public function destroy(Request $request, Measurement $measurement)
    {
        $measurement->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }
}
