<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\WorkerService;

class WorkerController extends Controller
{
	private $workerService;

	public function __construct()
	{
		$this->workerService = new WorkerService();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return response()->json($this->workerService->index());
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
		return response()->json($this->workerService->store($request));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\RelationCategory  $relationCategory
	 * @return \Illuminate\Http\Response
	 */
	public function show($user)
	{
		return response()->json($this->workerService->show($user));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\RelationCategory  $relationCategory
	 * @return \Illuminate\Http\Response
	 */
	public function edit(User $user)
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
	public function update(Request $request, $user)
	{
		return response()->json($this->workerService->update($request, $user));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\RelationCategory  $relationCategory
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($user)
	{
		return response()->json($this->workerService->destroy($user));
	}
}
