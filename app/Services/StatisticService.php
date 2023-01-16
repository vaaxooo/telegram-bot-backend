<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticService
{

	/**
	 * It returns the sum of all transactions for each day of the last week
	 * 
	 * @param request The request object.
	 * 
	 * @return An array with a key of weeklyStatistic and a value of the query result.
	 */
	public function weeklyStatistic($request)
	{

		$totalTransactions = Transaction::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count();
		$totalClients = Client::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count();
		$totalOrders = User::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count();

		$profit = Transaction::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'approved')->sum('amount');
		$unrealizedProfit = Transaction::where('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->where('status', 'pending')->sum('amount');

		//Profit for each manager's referral
		$managers = User::where('role', 'manager')->get();
		$managersProfit = [];
		foreach ($managers as $manager) {
			$managersProfit[$manager->name] = DB::table('transactions')->join('clients', 'transactions.client_id', '=', 'clients.id')
				->where('transactions.created_at', '>=', date('Y-m-d', strtotime('-7 days')))
				->where('transactions.status', 'approved')
				->where('client.referral', $manager->referral)
				->sum('transactions.amount');
		}

		return [
			'code' => 200,
			'message' => 'success',
			'data' => [
				'totalTransactions' => $totalTransactions,
				'totalClients' => $totalClients,
				'totalOrders' => $totalOrders,
				'profit' => $profit,
				'unrealizedProfit' => $unrealizedProfit,
				'managersProfit' => $managersProfit
			]
		];
	}
}
