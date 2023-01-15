<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->transactionService->index());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return response()->json($this->transactionService->show($transaction));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($transaction)
    {
        return response()->json($this->transactionService->destroy($transaction));
    }

    /**
     * It takes a request and a transaction, and returns a json response of the transaction service's
     * updateAmount function.
     * 
     * @param Request request The request object
     * @param transaction The transaction ID
     * 
     * @return The response is being returned as a json object.
     */
    public function updateAmount(Request $request, $transaction)
    {
        return response()->json($this->transactionService->updateAmount($request, $transaction));
    }

    /**
     * It sets the status of the transaction.
     * 
     * @param Request request The request object
     * @param transaction The transaction object that you want to set the status for.
     * 
     * @return The response is being returned as a json object.
     */
    public function setStatus(Request $request, $transaction)
    {
        return response()->json($this->transactionService->setStatus($request, $transaction));
    }
}
