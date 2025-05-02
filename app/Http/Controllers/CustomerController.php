<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'required',
            'cnh' => 'required|unique:customers',
        ]);

        return Customer::create($validated);
    }

    public function show($id)
    {
        return Customer::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes',
            'email' => 'sometimes|email|unique:customers,email,' . $id,
            'phone' => 'sometimes',
            'cnh' => 'sometimes|unique:customers,cnh,' . $id,
        ]);

        $customer->update($validated);

        return $customer;
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted']);
    }
}