<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Currency;
use Illuminate\Http\Request;

class RateController extends Controller {

    public function index(Request $request)
    {
        $perPage = 12;
        $rates = Rate::with(['baseCurrency', 'targetCurrency']);

        // filter for date
        if ($request->has('date')) {
            $rates->whereDate('effective_date', $request->date);
        }

        $rates = $rates->paginate($perPage);

        return inertia('Rate/Index', compact('rates'));

    }
}