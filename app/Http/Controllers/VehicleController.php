<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\App;

class VehicleController extends Controller
{
    public function index()
    {
        return Vehicle::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate' => 'required|unique:vehicles',
            'make' => 'required|string',
            'model' => 'required|string',
            'daily_rate' => 'required|numeric',
        ]);

        return Vehicle::create($validated);
    }

    public function show($id)
    {
        return Vehicle::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'plate' => 'sometimes|unique:vehicles,plate,' . $id,
            'make' => 'sometimes|string',
            'model' => 'sometimes|string',
            'daily_rate' => 'sometimes|numeric',
        ]);

        $vehicle->update($validated);

        return $vehicle;
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted']);
    }

    public function search(Request $request)
{
    $q = $request->query('q');

    /** @var Elastic\Elasticsearch\Client $elasticsearch */
    $elasticsearch = App::make(Client::class);

    $results = $elasticsearch->search([
        'index' => 'vehicles',
        'body'  => [
            'query' => [
                'multi_match' => [
                    'query' => $q,
                    'fields' => ['plate', 'make', 'model'],
                ],
            ],
        ],
    ]);

    $vehicles = array_map(fn($hit) => $hit['_source'], $results['hits']['hits']);

    return response()->json([
        'data' => $vehicles,
        'total' => $results['hits']['total']['value'] ?? 0,
    ]);
}
}
