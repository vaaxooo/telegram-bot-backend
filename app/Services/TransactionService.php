<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

class TransactionService
{
	private const STATUSES = [
		'pending',
		'approved',
		'rejected',
	];

	/**
	 * It returns a JSON response with a 200 status code, a success status, and the data from the database
	 * 
	 * @return An array with the following keys:
	 * - code
	 * - status
	 * - data
	 */
	public function index()
	{
		$transactions = Transaction::orderBy('id', 'desc')->get();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $transactions,
		];
	}

	/**
	 * It returns a JSON response with a 200 status code, a success status, and the transaction data
	 * 
	 * @param transaction The transaction object that was returned from the create method.
	 * 
	 * @return An array with the key 'data' and the value of the transaction.
	 */
	public function show($transaction)
	{
		$transaction->client = Client::where('id', $transaction->client_id)->first();
		return [
			'code' => 200,
			'status' => 'success',
			'data' => $transaction,
		];
	}

	/**
	 * It deletes a transaction from the database
	 * 
	 * @param transaction The transaction ID
	 * 
	 * @return An array with the code, status, and message.
	 */
	public function destroy($transaction)
	{
		Transaction::where('id', $transaction)->delete();
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Transaction deleted successfully',
		];
	}

	/**
	 * It updates the amount of a transaction
	 * 
	 * @param request The request object
	 * @param transaction The transaction ID
	 * 
	 * @return An array with the following keys:
	 */
	public function updateAmount($request, $transaction)
	{
		$validator = Validator::make($request->all(), [
			'amount' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => $validator->errors()->first(),
			];
		}
		Transaction::where('id', $transaction)->update([
			'amount' => $request->amount,
		]);
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Transaction updated successfully',
		];
	}

	/**
	 * It updates the status of a transaction and if the status is approved, it adds the amount to the
	 * client's balance
	 * 
	 * @param request The request object
	 * @param transaction The transaction ID
	 */
	public function setStatus($request, $transaction)
	{
		$validator = Validator::make($request->all(), [
			'status' => 'required,'
		]);
		if ($validator->fails()) {
			return [
				'code' => 400,
				'status' => 'error',
				'message' => $validator->errors()->first(),
			];
		}
		Transaction::where('id', $transaction)->update([
			'status' => $request->status,
		]);
		if ($request->status === 'approved') {
			$transaction = Transaction::where('id', $transaction)->first();
			$client = Client::where('id', $transaction->client_id)->first();
			$client->balance += (int) $transaction->amount;
			$client->save();

			$worker = "Отсутствует";
			$user = Client::where('referral', $client->referral)->first();

			if ($user) {
				$worker = $user->name;
			}

			$tMessage = '*Новая транзакция*' . PHP_EOL;
			$tMessage .= '*Клиент:* ' . $client->telegram_id . PHP_EOL;
			$tMessage .= '*Сумма:* ' . $transaction->amount . PHP_EOL;
			$tMessage .= '*Статус:* Оплачено' . PHP_EOL;
			$tMessage .= '*Метод:* ' . $transaction->method . PHP_EOL;
			$tMessage .= '*Воркер:* ' . $worker;

			Telegram::sendMessage([
				'chat_id' => env('TELEGRAM_PAYMENTS_CHAT_ID'),
				'text' => $tMessage,
				'parse_mode' => 'Markdown',
			]);
		}
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Transaction updated successfully',
		];
	}
}
