<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        return Rental::with('vehicle', 'customer')->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        return Rental::create($validated);
    }

    public function start($id)
    {
        $rental = Rental::findOrFail($id);
        if ($rental->start_date) {
            return response()->json(['message' => 'Rental already started'], 400);
        }

        $rental->start_date = Carbon::now()->toDateString();
        $rental->save();

        return $rental;
    }

    public function end($id)
    {
        $rental = Rental::with('vehicle')->findOrFail($id);

        if (!$rental->start_date) {
            return response()->json(['message' => 'Rental not started yet'], 400);
        }

        if ($rental->end_date) {
            return response()->json(['message' => 'Rental already ended'], 400);
        }

        $start = Carbon::parse($rental->start_date);
        $end = Carbon::now();
        $days = max($start->diffInDays($end), 1);

        $rental->end_date = $end->toDateString();
        $rental->total_amount = $days * $rental->vehicle->daily_rate;
        $rental->save();

        return $rental;
    }

    public function show($id)
    {
        return Rental::with('vehicle', 'customer')->findOrFail($id);
    }
}

