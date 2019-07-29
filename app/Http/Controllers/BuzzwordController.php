<?php

namespace App\Http\Controllers;

use App\Buzzword;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BuzzwordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QueryBuilder::for(Buzzword::class)
            ->allowedIncludes(Buzzword::$allowedIncludes)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Buzzword  $buzzword
     * @return \Illuminate\Http\Response
     */
    public function show(Buzzword $buzzword)
    {
        return QueryBuilder::for(Buzzword::where('id', $buzzword->id))
            ->allowedIncludes(Buzzword::$allowedIncludes)
            ->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Buzzword  $buzzword
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buzzword $buzzword)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Buzzword  $buzzword
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buzzword $buzzword)
    {
        //
    }
}
