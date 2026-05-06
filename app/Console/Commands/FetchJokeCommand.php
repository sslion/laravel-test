<?php
namespace App\Console\Commands;

use App\Models\Joke;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchJokeCommand extends Command
{
    protected $signature = 'jokes:fetch';
    protected $description = 'Получает случайную шутку из API и сохраняет в БД';

    public function handle(): void
    {
        $this->info('Начинаем получение шутки...');
        
        try {
            $response = Http::get('https://official-joke-api.appspot.com/random_joke');
            
            if ($response->successful()) {
                $data = $response->json();
                
                Joke::create([
                    'joke_id' => $data['id'],
                    'type' => $data['type'],
                    'setup' => $data['setup'],
                    'punchline' => $data['punchline'],
                    'fetched_at' => now(),
                ]);
                
                $this->info("Шутка #{$data['id']} успешно сохранена!");
                $this->line("Тип: {$data['type']}");
                $this->line("Текст: {$data['setup']}");
            } else {
                $this->error('Ошибка при получении данных от API');
            }
        } catch (\Exception $e) {
            $this->error('Произошла ошибка: ' . $e->getMessage());
            \Log::error('Ошибка получения шутки: ' . $e->getMessage());
        }
    }
}