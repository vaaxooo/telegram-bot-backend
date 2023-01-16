<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\StatisticService;

class TransactionController extends Controller
{
	private $statisticService;

	public function __construct()
	{
		$this->statisticService = new StatisticService();
	}


	/**
	 * It returns a JSON response of the weekly statistic
	 * 
	 * @param Request request The request object.
	 * 
	 * @return The weeklyStatistic method is returning a JSON response.
	 */
	public function weeklyStatistic(Request $request)
	{
		return response()->json($this->statisticService->weeklyStatistic());
	}
}
