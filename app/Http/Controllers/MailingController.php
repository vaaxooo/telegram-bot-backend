<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailingService;

class MailingController extends Controller
{
	private $mailingService;

	public function __construct()
	{
		$this->mailingService = new MailingService();
	}

	/**
	 * It takes a request, passes it to the mailing service, and returns the response as JSON
	 * 
	 * @param Request request The request object
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function send(Request $request)
	{
		return response()->json($this->mailingService->send($request));
	}

	/**
	 * It takes a request, passes it to the mailing service, and returns the response as a JSON object
	 * 
	 * @param Request request 
	 * 
	 * @return The response is being returned as a JSON object.
	 */
	public function sendByTelegramId(Request $request)
	{
		return response()->json($this->mailingService->sendByTelegramId($request));
	}
}
