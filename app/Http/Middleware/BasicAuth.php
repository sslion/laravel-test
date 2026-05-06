<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{
    /**
     * Простая HTTP Basic авторизация
     * Логин: admin
     * Пароль: secret123
     */
    public function handle(Request $request, Closure $next)
    {
        // Получаем заголовок авторизации
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
            return $this->requestAuth();
        }
        
        // Декодируем логин и пароль
        $credentials = base64_decode(substr($authHeader, 6));
        list($username, $password) = explode(':', $credentials, 2);
        
        // Проверяем (для тестового задания используем захардкоженные значения)
        if ($username !== 'admin' || $password !== 'secret123') {
            return $this->requestAuth();
        }
        
        return $next($request);
    }
    
    /**
     * Запрос авторизации
     */
    private function requestAuth()
    {
        return response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic realm="Stats Dashboard"'
        ]);
    }
}