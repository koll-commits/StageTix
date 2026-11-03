<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->attributes->get('tenant');

        return view('eo.balance', [
            'balance' => $tenant->balance,
            'accounts' => $tenant->bankAccounts()->orderByDesc('is_primary')->get(),
            'withdrawals' => Withdrawal::where('tenant_id', $tenant->id)->latest()->limit(10)->get(),
        ]);
    }
}
