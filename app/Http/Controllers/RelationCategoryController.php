<?php

namespace App\Http\Controllers;

use App\Models\RelationCategory;
use Illuminate\Http\Request;
use App\Services\RelationCategoryService;

class RelationCategoryController extends Controller
{
    private $relationCategoryService;

    public function __construct()
    {
        $this->relationCategoryService = new RelationCategoryService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->relationCategoryService->index());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json($this->relationCategoryService->store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RelationCategory  $relationCategory
     * @return \Illuminate\Http\Response
     */
    public function show(RelationCategory $relationCategory)
    {
        return response()->json($this->relationCategoryService->show($relationCategory));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RelationCategory  $relationCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(RelationCategory $relationCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RelationCategory  $relationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RelationCategory $relationCategory)
    {
        return response()->json($this->relationCategoryService->update($request, $relationCategory));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RelationCategory  $relationCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(RelationCategory $relationCategory)
    {
        return response()->json($this->relationCategoryService->destroy($relationCategory));
    }
}
