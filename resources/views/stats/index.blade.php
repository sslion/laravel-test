<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика посещений</title>
    <!-- Подключаем Chart.js для графиков -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chart-container h2 {
            margin-bottom: 1rem;
            color: #444;
        }
        
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Статистика посещений</h1>
        <p>Данные обновляются в реальном времени</p>
    </div>
    
    <div class="container">
        <!-- Карточки с общей статистикой -->
        <div class="stats-cards">
            <div class="stat-card">
                <h3>Посещений сегодня</h3>
                <div class="value" id="todayVisits">Загрузка...</div>
            </div>
            <div class="stat-card">
                <h3>Уникальных посетителей</h3>
                <div class="value" id="uniqueVisitors">Загрузка...</div>
            </div>
            <div class="stat-card">
                <h3>Всего посещений</h3>
                <div class="value" id="totalVisits">Загрузка...</div>
            </div>
        </div>
        
        <!-- Графики -->
        <div class="charts-grid">
            <!-- Почасовой график -->
            <div class="chart-container">
                <h2>Посещения по часам (сегодня)</h2>
                <canvas id="hourlyChart"></canvas>
            </div>
            
            <!-- Круговая диаграмма по городам -->
            <div class="chart-container">
                <h2>Распределение по городам</h2>
                <canvas id="cityChart"></canvas>
            </div>
        </div>
    </div>
    
    <script>
        // Глобальные переменные для графиков
        let hourlyChart = null;
        let cityChart = null;
        
        // Цветовая палитра для графиков
        const COLORS = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF9F40',
            '#8B4513', '#2E8B57', '#A0522D', '#6A5ACD', '#4169E1'
        ];
        
        /**
         * Инициализация при загрузке страницы
         */
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Инициализация дашборда статистики');
            
            // Загружаем все данные
            loadGeneralStats();
            loadHourlyChart();
            loadCityChart();
            
            // Обновление каждые 30 секунд
            setInterval(loadGeneralStats, 30000);
            setInterval(loadHourlyChart, 30000);
            setInterval(loadCityChart, 60000);
        });
        
        /**
         * Загрузка общей статистики
         */
        function loadGeneralStats() {
            fetch('/api/stats/general')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Ошибка сети');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Общая статистика:', data);
                    
                    // Обновляем значения на странице
                    document.getElementById('todayVisits').textContent = 
                        data.today_visits.toLocaleString();
                    document.getElementById('uniqueVisitors').textContent = 
                        data.unique_visitors.toLocaleString();
                    document.getElementById('totalVisits').textContent = 
                        data.total_visits.toLocaleString();
                })
                .catch(error => {
                    console.error('Ошибка загрузки общей статистики:', error);
                    document.getElementById('todayVisits').textContent = 'Ошибка';
                    document.getElementById('uniqueVisitors').textContent = 'Ошибка';
                    document.getElementById('totalVisits').textContent = 'Ошибка';
                });
        }
        
        /**
         * Загрузка и отрисовка почасового графика
         */
        function loadHourlyChart() {
            fetch('/api/stats/hourly')
                .then(response => response.json())
                .then(data => {
                    console.log('Почасовая статистика:', data);
                    
                    const ctx = document.getElementById('hourlyChart').getContext('2d');
                    
                    // Если график уже существует - уничтожаем
                    if (hourlyChart) {
                        hourlyChart.destroy();
                    }
                    
                    // Создаем новый график
                    hourlyChart = new Chart(ctx, {
                        type: 'line',  // Линейный график
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Уникальные посещения',
                                data: data.unique,
                                borderColor: '#667eea',
                                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,  // Сглаживание линии
                                fill: true
                            }, {
                                label: 'Всего посещений',
                                data: data.total,
                                borderColor: '#764ba2',
                                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Время (часы)'
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Ошибка загрузки почасовой статистики:', error);
                });
        }
        
        /**
         * Загрузка и отрисовка круговой диаграммы по городам
         */
        function loadCityChart() {
            fetch('/api/stats/cities')
                .then(response => response.json())
                .then(data => {
                    console.log('Статистика по городам:', data);
                    
                    const ctx = document.getElementById('cityChart').getContext('2d');
                    
                    // Если график уже существует - уничтожаем
                    if (cityChart) {
                        cityChart.destroy();
                    }
                    
                    // Создаем круговую диаграмму
                    cityChart = new Chart(ctx, {
                        type: 'pie',  // Круговая диаграмма
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: COLORS.slice(0, data.labels.length),
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const value = context.parsed;
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return ` ${context.label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Ошибка загрузки статистики по городам:', error);
                });
        }
    </script>
</body>
</html>