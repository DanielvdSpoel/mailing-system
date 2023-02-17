<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(Request $request)
    {
        $labels = Label::query();

        if ($request->get('search')) {
            $labels->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->get('limit')) {
            $labels->limit($request->get('limit'));
        }

        $labels->orderBy('name');

        return LabelResource::collection($labels->get());
    }

    public function show(Label $label)
    {
        return new LabelResource($label);
    }
}
