<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    public function handle(Request $request, $service, $path = '')
    {
        $baseUrl = match ($service) {
            'carreras' => config('services.carreras.base_url'),
            'grupos' => config('services.grupos.base_url'),
            'inscripciones' => config('services.inscripciones.base_url'),
            'usuarios' => config('services.usuarios.base_url'),
            'materias' => config('services.materias.base_url'),
            default => null
        };

        if (!$baseUrl) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }

        // Si no hay un path, no agregamos "/api/" al final
        $url = rtrim($baseUrl, '/');

        if ($path !== '') {
            $url .= '/api/' . ltrim($path, '/');
        } else {
            $url .= '/api'; // raíz del microservicio
        }

        $response = Http::withHeaders($request->headers->all())
            ->send($request->method(), $url, [
                'query' => $request->query(),
                'json'  => $request->all(),
            ]);

        return response($response->body(), $response->status())
            ->withHeaders($response->headers());
    }
}
