<?php

namespace CloudSoh\InventoryManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use CloudSoh\InventoryManagement\Metric;
use CloudSoh\InventoryManagement\Transformers\MetricTransformer;

class MetricController extends Controller
{
    public function __construct()
    {
        $this->middleware('snake');
    }

    private function validationRules($id = null)
    {
        return [
            'name' => "required|string|unique:metrics,name,$id,id,deleted_at,NULL",
            'symbol' => "required|string|unique:metrics,symbol,$id,id,deleted_at,NULL",
        ];
    }

    public function index()
    {
        return responder()->success(Metric::all(), MetricTransformer::class)->with('measurements')->respond(Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->validationRules());

        return responder()->success(Metric::create($data), MetricTransformer::class)->respond(Response::HTTP_CREATED);
    }

    public function show(Request $request, Metric $metric)
    {
        return responder()->success($metric, MetricTransformer::class)->respond(Response::HTTP_OK);
    }

    public function update(Request $request, Metric $metric)
    {
        $data = $this->validate($request, $this->validationRules($metric->id));

        $metric->update($data);

        return responder()->success($metric, MetricTransformer::class)->respond(Response::HTTP_OK);
    }

    public function destroy(Request $request, Metric $metric)
    {
        $metric->delete();

        return responder()->success()->respond(Response::HTTP_NO_CONTENT);
    }
}
