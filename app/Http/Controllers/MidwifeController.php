<?php

namespace App\Http\Controllers;

use App\Models\Midwife;
use App\Http\Requests\StoreMidwifeRequest;
use App\Http\Requests\UpdateMidwifeRequest;

class MidwifeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMidwifeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMidwifeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Midwife  $midwife
     * @return \Illuminate\Http\Response
     */
    public function show(Midwife $midwife)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Midwife  $midwife
     * @return \Illuminate\Http\Response
     */
    public function edit(Midwife $midwife)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMidwifeRequest  $request
     * @param  \App\Models\Midwife  $midwife
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMidwifeRequest $request, Midwife $midwife)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Midwife  $midwife
     * @return \Illuminate\Http\Response
     */
    public function destroy(Midwife $midwife)
    {
        //
    }
}
