<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Payment;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/payments/ajax', function (Request $request) {
    $query = Payment::query();
    
    // Search filter
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('customer_email', 'LIKE', "%{$searchTerm}%")
              ->orWhere('customer_id', 'LIKE', "%{$searchTerm}%")
              ->orWhere('provider', 'LIKE', "%{$searchTerm}%")
              ->orWhere('amount', 'LIKE', "%{$searchTerm}%")
              ->orWhere('status', 'LIKE', "%{$searchTerm}%")
              ->orWhere('event_type', 'LIKE', "%{$searchTerm}%")
              ->orWhere('resource_id', 'LIKE', "%{$searchTerm}%");
        });
    }
    
    // Filter by dropdown selections (multiple values)
    if ($request->has('status') && is_array($request->status) && !empty($request->status)) {
        $statuses = array_map('strtolower', $request->status);
        // Map frontend status values to database status values
        $mappedStatuses = [];
        foreach ($statuses as $status) {
            switch ($status) {
                case 'succeeded':
                    $mappedStatuses[] = 'completed';
                    break;
                case 'failed':
                    $mappedStatuses[] = 'failed';
                    break;
                case 'pending':
                    $mappedStatuses[] = 'pending';
                    break;
                case 'refunded':
                    $mappedStatuses[] = 'refunded';
                    break;
                case 'cancelled':
                    $mappedStatuses[] = 'cancelled';
                    break;
                default:
                    $mappedStatuses[] = $status;
            }
        }
        $query->whereIn('status', $mappedStatuses);
    }
    
    if ($request->has('payment-method') && is_array($request->input('payment-method')) && !empty($request->input('payment-method'))) {
        $query->whereIn('payment_method', $request->input('payment-method'));
    }
    
    if ($request->has('gateway') && is_array($request->gateway) && !empty($request->gateway)) {
        $query->whereIn('provider', array_map('strtolower', $request->gateway));
    }
    
    if ($request->has('date') && is_array($request->date) && !empty($request->date)) {
        // Handle date ranges
        $dateFilters = $request->date;
        $query->where(function($q) use ($dateFilters) {
            foreach ($dateFilters as $range) {
                switch ($range) {
                    case 'today':
                        $q->orWhereDate('received_at', today());
                        break;
                    case 'yesterday':
                        $q->orWhereDate('received_at', today()->subDay());
                        break;
                    case 'last-7-days':
                        $q->orWhere('received_at', '>=', now()->subDays(7));
                        break;
                    case 'last-30-days':
                        $q->orWhere('received_at', '>=', now()->subDays(30));
                        break;
                    case 'last-90-days':
                        $q->orWhere('received_at', '>=', now()->subDays(90));
                        break;
                }
            }
        });
    }
    
    // Handle custom date range filter
    if ($request->has('custom_from_date') || $request->has('custom_to_date')) {
        $fromDate = $request->get('custom_from_date');
        $toDate = $request->get('custom_to_date');
        
        if ($fromDate && $toDate) {
            // Both dates provided - range filter
            $query->whereBetween('received_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif ($fromDate) {
            // Only from date provided - from date onwards
            $query->where('received_at', '>=', $fromDate . ' 00:00:00');
        } elseif ($toDate) {
            // Only to date provided - up to to date
            $query->where('received_at', '<=', $toDate . ' 23:59:59');
        }
    }
    
    if ($request->has('more') && is_array($request->more) && !empty($request->more)) {
        // Handle additional filters
        $moreFilters = $request->more;
        foreach ($moreFilters as $filter) {
            switch ($filter) {
                case 'test':
                    $query->where('is_test', true);
                    break;
                case 'live':
                    $query->where('is_test', false);
                    break;
                case 'disputed':
                    $query->where('status', 'like', '%dispute%');
                    break;
                case 'refunded':
                    $query->where('status', 'refunded');
                    break;
            }
        }
    }
    
    // Filter by test/live payments
    if ($request->has('test_mode')) {
        if ($request->test_mode === 'true') {
            $query->onlyTest();
        } else {
            $query->excludeTest();
        }
    }
    
    // Pagination
    $perPage = $request->get('per_page', 5);
    $page = $request->get('page', 1);
    
    $totalCount = $query->count();
    $payments = $query->orderBy('received_at', 'desc')
                     ->skip(($page - 1) * $perPage)
                     ->take($perPage)
                     ->get();
    
    return response()->json([
        'data' => $payments,
        'current_page' => (int) $page,
        'per_page' => (int) $perPage,
        'total' => $totalCount,
        'last_page' => ceil($totalCount / $perPage),
        'from' => ($page - 1) * $perPage + 1,
        'to' => min($page * $perPage, $totalCount)
    ]);
});

// Route to get filter options dynamically
Route::get('/filters/options', function () {
    // Get unique providers from database
    $gateways = Payment::select('provider')
        ->distinct()
        ->whereNotNull('provider')
        ->where('provider', '!=', '')
        ->pluck('provider')
        ->map(function($provider) {
            return [
                'value' => $provider,
                'label' => ucfirst($provider),
                'icon' => getGatewayIcon($provider)
            ];
        });

    // Get unique statuses from database
    $statuses = Payment::select('status')
        ->distinct()
        ->whereNotNull('status')
        ->where('status', '!=', '')
        ->pluck('status')
        ->map(function($status) {
            return [
                'value' => $status,
                'label' => ucfirst($status),
                'icon' => getStatusIcon($status)
            ];
        });

    // Get unique payment methods from database
    $paymentMethods = Payment::select('payment_method')
        ->distinct()
        ->whereNotNull('payment_method')
        ->where('payment_method', '!=', '')
        ->pluck('payment_method')
        ->map(function($method) {
            return [
                'value' => $method,
                'label' => ucfirst(str_replace('_', ' ', $method)),
                'icon' => getPaymentMethodIcon($method)
            ];
        });

    // Get unique currencies from database
    $currencies = Payment::select('currency')
        ->distinct()
        ->whereNotNull('currency')
        ->where('currency', '!=', '')
        ->pluck('currency')
        ->map(function($currency) {
            return [
                'value' => $currency,
                'label' => strtoupper($currency),
                'icon' => 'fa fa-dollar-sign'
            ];
        });

    // Predefined amount ranges
    $amountRanges = [
        ['value' => 'under-10', 'label' => 'Under $10', 'icon' => 'fa fa-dollar-sign'],
        ['value' => '10-50', 'label' => '$10 - $50', 'icon' => 'fa fa-dollar-sign'],
        ['value' => '50-100', 'label' => '$50 - $100', 'icon' => 'fa fa-dollar-sign'],
        ['value' => 'over-100', 'label' => 'Over $100', 'icon' => 'fa fa-dollar-sign'],
    ];

    return response()->json([
        'gateways' => $gateways,
        'statuses' => $statuses,
        'payment' => $paymentMethods,
        'paymentgateway' => $gateways,
        'currency' => $currencies,
        'amount' => $amountRanges
    ]);
});

// Helper functions for icons
function getGatewayIcon($gateway) {
    switch(strtolower($gateway)) {
        case 'stripe': return 'fa-brands fa-stripe';
        case 'paypal': return 'fa-brands fa-paypal';
        case 'square': return 'fas fa-square';
        default: return 'fa fa-money';
    }
}

function getStatusIcon($status) {
    switch(strtolower($status)) {
        case 'paid': return 'fa fa-check-circle';
        case 'pending': return 'fa fa-clock';
        case 'due': return 'fa fa-exclamation-triangle';
        case 'unpaid': return 'fa fa-times-circle';
        case 'failed': return 'fa fa-ban';
        case 'upcoming': return 'fa fa-calendar';
        default: return 'fa fa-circle';
    }
}

function getPaymentMethodIcon($method) {
    switch(strtolower($method)) {
        case 'visa': return 'fa-brands fa-cc-visa';
        case 'mastercard': return 'fa-brands fa-cc-mastercard';
        case 'amex': return 'fa-brands fa-cc-amex';
        case 'discover': return 'fa-brands fa-cc-discover';
        case 'paypal': return 'fa-brands fa-paypal';
        case 'apple_pay': return 'fa-brands fa-apple-pay';
        case 'google_pay': return 'fa-brands fa-google-pay';
        default: return 'fa fa-credit-card';
    }
}
