<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TreatmentsController extends Controller
{
    public function index()
    {
        return view('treatments.index');
    }
}