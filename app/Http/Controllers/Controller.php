<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    //
}

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();
        if ($request->has('gateway') && in_array($request->gateway, ['stripe', 'paypal', 'square'])) {
            $query->where('gateway', $request->gateway);
        }
        $payments = $query->orderBy('date', 'desc')->get();
        Log::info('Payments fetched', ['count' => $payments->count(), 'data' => $payments->toArray()]);
        return response()->json($payments);
    }
}
