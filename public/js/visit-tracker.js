/**
 * Visit Tracker - скрипт для сбора статистики посещений
 * Можно подключить к любому сайту
 */
(function() {
    'use strict';
    
    // Конфигурация
    const CONFIG = {
        // Куда отправлять данные
        endpoint: 'http://laravel-test.loc/api/collect-visit',
        
        // Задержка перед отправкой (миллисекунды)
        delay: 1000,
        
        // Отправлять ли данные о местоположении
        collectGeo: true
    };
    
    /**
     * Определение типа устройства
     */
    function getDeviceType() {
        const ua = navigator.userAgent;
        
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return 'Tablet';
        }
        if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            return 'Mobile';
        }
        return 'Desktop';
    }
    
    /**
     * Определение браузера
     */
    function getBrowser() {
        const ua = navigator.userAgent;
        
        if (ua.includes('Firefox')) return 'Firefox';
        if (ua.includes('SamsungBrowser')) return 'Samsung Browser';
        if (ua.includes('Opera') || ua.includes('OPR')) return 'Opera';
        if (ua.includes('Edge')) return 'Edge';
        if (ua.includes('Chrome')) return 'Chrome';
        if (ua.includes('Safari')) return 'Safari';
        
        return 'Unknown';
    }
    
    /**
     * Сбор данных и отправка на сервер
     */
    function collectAndSend() {
        // Основные данные
        const data = {
            page_url: window.location.href,
            device_type: getDeviceType(),
            browser: getBrowser(),
            user_agent: navigator.userAgent,
            screen_resolution: `${window.screen.width}x${window.screen.height}`,
            referrer: document.referrer || 'Direct',
            timestamp: new Date().toISOString()
        };
        
        /**
         * Функция отправки данных
         */
        function sendData(extraData = {}) {
            const finalData = { ...data, ...extraData };
            
            // Отправляем на сервер
            fetch(CONFIG.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(finalData)
            })
            .then(response => response.json())
            .then(result => {
                console.log('Visit tracked:', result);
            })
            .catch(error => {
                console.warn('Failed to track visit:', error);
                
                // Повторная попытка через 5 секунд
                setTimeout(() => collectAndSend(), 5000);
            });
        }
        
        // Если нужно собирать гео-данные
        if (CONFIG.collectGeo) {
            // Используем бесплатный API для определения местоположения
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(geo => {
                    sendData({
                        ip_address: geo.ip,
                        city: geo.city,
                        country: geo.country_name
                    });
                })
                .catch(() => {
                    // Если не удалось получить гео, отправляем без него
                    sendData();
                });
        } else {
            sendData();
        }
    }
    
    // Отправляем данные после загрузки страницы
    if (document.readyState === 'complete') {
        setTimeout(collectAndSend, CONFIG.delay);
    } else {
        window.addEventListener('load', () => {
            setTimeout(collectAndSend, CONFIG.delay);
        });
    }
    
})();