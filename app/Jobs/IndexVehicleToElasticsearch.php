<?php

namespace App\Jobs;

use App\Models\Vehicle;
use Elastic\Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IndexVehicleToElasticsearch implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public Vehicle $vehicle) {}

    public function handle(Client $elasticsearch)
{
    \Log::info("Indexando veículo ID: " . $this->vehicle->id);

    try {
        $elasticsearch->index([
            'index' => 'vehicles',
            'id'    => $this->vehicle->id,
            'body'  => $this->vehicle->only(['plate', 'make', 'model', 'daily_rate']),
        ]);
    } catch (\Exception $e) {
        \Log::error("Erro ao indexar veículo ID: " . $this->vehicle->id . ' - ' . $e->getMessage());
        throw $e;
    }
}
    
}
