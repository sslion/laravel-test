/**
 * Скрипт фильтрации полей по типу
 * 
 * АЛГОРИТМ РЕШЕНИЯ:
 * 1. Находим select с типами на странице
 * 2. При изменении select проверяем каждое поле ввода
 * 3. Если атрибут name поля содержит выбранный тип - показываем
 * 4. Если не содержит - скрываем
 * 
 * АЛЬТЕРНАТИВНЫЕ ВАРИАНТЫ И ПОЧЕМУ НЕ ВЫБРАНЫ:
 * 
 * 1. Data-атрибуты (data-type):
 *    Плюсы: Более читаемый код, независимость от name
 *    Минусы: Требует изменения HTML, что не всегда возможно
 * 
 * 2. Классы CSS (.type-programming):
 *    Плюсы: Простая реализация через toggle
 *    Минусы: Усложняет верстку, нужно синхронизировать классы
 * 
 * 3. Группировка в отдельные контейнеры:
 *    Плюсы: Быстрый показ/скрытие групп
 *    Минусы: Требует полной переработки HTML структуры
 * 
 * ВЫБРАННЫЙ ВАРИАНТ: Фильтрация по атрибуту name
 *    - Работает с существующей разметкой
 *    - Не требует изменений HTML
 *    - Устойчив к изменениям структуры страницы
 */

(function() {
    'use strict';
    
    // Ждем полной загрузки DOM
    document.addEventListener('DOMContentLoaded', function() {
        
        // Ищем select по разным возможным селекторам
        const selectField = findTypeSelect();
        
        if (!selectField) {
            console.warn('FieldFilter: Select с типами не найден');
            return;
        }
        
        console.log('FieldFilter: Инициализация', {
            selectId: selectField.id,
            optionsCount: selectField.options.length
        });
        
        /**
         * Основная функция фильтрации
         */
        function filterFields() {
            const selectedType = selectField.value.toLowerCase();
            
            console.log('FieldFilter: Фильтрация по типу:', selectedType);
            
            // Если выбран пустой тип - показываем все
            if (!selectedType) {
                showAllFields();
                return;
            }
            
            // Получаем все поля ввода
            const allFields = document.querySelectorAll('input, select, textarea');
            
            let visibleCount = 0;
            let hiddenCount = 0;
            
            allFields.forEach(function(field) {
                const fieldName = (field.getAttribute('name') || '').toLowerCase();
                const container = findFieldContainer(field);
                
                if (!container) return;
                
                // Проверяем, содержит ли name выбранный тип
                if (fieldName.includes(selectedType)) {
                    container.style.display = '';
                    visibleCount++;
                } else {
                    container.style.display = 'none';
                    hiddenCount++;
                }
            });
            
            console.log(`FieldFilter: Показано ${visibleCount}, скрыто ${hiddenCount} полей`);
        }
        
        /**
         * Показать все поля
         */
        function showAllFields() {
            document.querySelectorAll('input, select, textarea').forEach(function(field) {
                const container = findFieldContainer(field);
                if (container) {
                    container.style.display = '';
                }
            });
        }
        
        /**
         * Найти контейнер поля
         * Ищем родительский элемент, который можно скрыть
         */
        function findFieldContainer(field) {
            // Пробуем разные уровни вложенности
            let container = field.closest('tr');
            if (!container) {
                container = field.closest('.form-group');
            }
            if (!container) {
                container = field.closest('div');
            }
            if (!container) {
                container = field.parentElement;
            }
            return container;
        }
        
        /**
         * Найти select с типами
         * Пробуем разные стратегии поиска
         */
        function findTypeSelect() {
            // Стратегия 1: По ID содержащему 'type'
            let select = document.querySelector('select[id*="type"], select[id*="Type"]');
            
            // Стратегия 2: Первый select на странице
            if (!select) {
                select = document.querySelector('select');
            }
            
            return select;
        }
        
        // Вешаем обработчик на изменение select
        selectField.addEventListener('change', filterFields);
        
        // Запускаем фильтрацию при загрузке
        filterFields();
        
        console.log('FieldFilter: Готов к работе');
    });
})();