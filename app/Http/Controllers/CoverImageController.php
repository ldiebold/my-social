<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoverImageController extends Controller
{
    public function standard()
    {
        return view('image-templates.dynamic-image');
    }
}
