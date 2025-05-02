<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        try {
            $response = Http::timeout(3)->get(env('REPORT_SERVICE_URL') . '/reports/revenue', [
                'start' => $request->start,
                'end' => $request->end,
            ]);

            if ($response->failed()) {
                Log::warning('Serviço de relatório retornou erro.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'error' => 'Relatório indisponível'
                ], 500);
            }

            return response()->json($response->json(), 200);
        } catch (\Exception $e) {
            Log::error('Erro ao consultar serviço de relatório: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao acessar o serviço de relatórios.'
            ], 500);
        }
    }
}
