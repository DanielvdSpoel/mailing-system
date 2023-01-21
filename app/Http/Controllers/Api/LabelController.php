<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelResource;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return LabelResource::collection(Label::all());
    }

    public function show(Label $label)
    {
        return new LabelResource($label);
    }
}
