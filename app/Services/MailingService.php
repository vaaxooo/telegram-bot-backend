<?php

namespace App\Services;

use App\Models\Client;
use Telegram\Bot\Laravel\Facades\Telegram;

class MailingService
{

	/**
	 * It gets all the clients, loops through them, and sends a message to each one
	 * 
	 * @param request The request object.
	 */
	public function send($request)
	{
		$clients = Client::get();
		$clients->each(function ($client) use ($request) {
			Telegram::sendMessage([
				'chat_id' => $client->telegram_id,
				'text' => $request->message,
				'parse_mode' => 'markdown'
			]);
		});
		return [
			'code' => 200,
			'status' => 'success',
			'message' => 'Message sent',
		];
	}

	/**
	 * > It sends a message to a Telegram user by their Telegram ID
	 * 
	 * @param request The request object.
	 */
	public function sendByTelegramId($request)
	{
		try {
			$client = Client::where('telegram_id', $request->telegram_id)->first();
			if (!$client) {
				return [
					'status' => 'error',
					'message' => 'Client not found',
				];
			}
			Telegram::sendMessage([
				'chat_id' => $request->telegram_id,
				'text' => $request->message,
				'parse_mode' => 'markdown'
			]);
			return [
				'code' => 200,
				'status' => 'success',
				'message' => 'Message sent',
			];
		} catch (\Exception $e) {
			return [
				'status' => 'error',
				'message' => 'Telegram bot is not connected',
			];
		}
	}
}
