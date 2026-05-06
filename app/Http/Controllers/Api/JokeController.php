<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Joke;
use Illuminate\Http\JsonResponse;

class JokeController extends Controller
{
    public function index(): JsonResponse
    {
        // Получаем все шутки, отсортированные по дате
        $jokes = Joke::orderBy('fetched_at', 'desc')
            ->paginate(20);  // По 20 шуток на страницу
        
        return response()->json([
            'success' => true,
            'data' => $jokes
        ]);
    }
    
    // Получить шутки по типу
    public function byType(string $type): JsonResponse
    {
        $jokes = Joke::where('type', $type)
            ->orderBy('fetched_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'type' => $type,
            'count' => $jokes->count(),
            'data' => $jokes
        ]);
    }
}