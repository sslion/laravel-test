<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Страница со статистикой
     */
    public function index(): View
    {
        return view('stats.index');
    }
    
    /**
     * API: Почасовая статистика
     */
    public function hourlyStats(): JsonResponse
    {
        // Группируем по часам и считаем уникальные IP
        $stats = Visit::select(
                DB::raw('HOUR(visited_at) as hour'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visits'),
                DB::raw('COUNT(*) as total_visits')
            )
            ->whereDate('visited_at', today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        return response()->json([
            'labels' => $stats->pluck('hour')->map(fn($h) => $h . ':00'),
            'unique' => $stats->pluck('unique_visits'),
            'total' => $stats->pluck('total_visits')
        ]);
    }
    
    /**
     * API: Статистика по городам
     */
    public function cityStats(): JsonResponse
    {
        $stats = Visit::select('city', DB::raw('COUNT(DISTINCT ip_address) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', 'Unknown')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        return response()->json([
            'labels' => $stats->pluck('city'),
            'data' => $stats->pluck('count')
        ]);
    }
    
    /**
     * API: Общая статистика
     */
    public function generalStats(): JsonResponse
    {
        return response()->json([
            'today_visits' => Visit::whereDate('visited_at', today())->count(),
            'unique_visitors' => Visit::whereDate('visited_at', today())
                ->distinct('ip_address')
                ->count(),
            'total_visits' => Visit::count(),
            'device_breakdown' => Visit::select('device_type', DB::raw('count(*) as count'))
                ->groupBy('device_type')
                ->get()
        ]);
    }
}