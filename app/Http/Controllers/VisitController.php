<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VisitController extends Controller
{
    /**
     * Принимает данные о посещении от JS трекера
     */
    public function collect(Request $request): JsonResponse
    {
        // Валидация входящих данных
        $validated = $request->validate([
            'page_url' => 'required|string',
            'ip_address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'device_type' => 'nullable|string',
            'browser' => 'nullable|string',
            'user_agent' => 'nullable|string',
            'screen_resolution' => 'nullable|string',
            'referrer' => 'nullable|string',
        ]);
        
        try {
            // Сохраняем визит
            $visit = Visit::create([
                'ip_address' => $validated['ip_address'] ?? $request->ip(),
                'city' => $validated['city'] ?? 'Unknown',
                'country' => $validated['country'] ?? 'Unknown',
                'device_type' => $validated['device_type'] ?? 'Unknown',
                'browser' => $validated['browser'] ?? 'Unknown',
                'user_agent' => $validated['user_agent'] ?? $request->userAgent(),
                'page_url' => $validated['page_url'],
                'visited_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'visit_id' => $visit->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to save visit'
            ], 500);
        }
    }
}