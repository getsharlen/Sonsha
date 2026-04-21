<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function topUp(Request $request): RedirectResponse
    {
        return app(DashboardController::class)->topUp($request);
    }
}