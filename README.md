# inn-check
Проверка ИНН на самозанятость

Использован фреймворк **Laminas**

Весь разработанный код находится в директории **./module/Employment**

## Установка
Приложение тестировалось на Ubuntu 20.04, версия docker - 19.03.8, Версия docker-compose - 1.25.0

Так как контейнер для фреймворка использован встроенный, нужно обновить репозитории composer в папке куда склонирован репозиторий до запуска контейнеров. Так как в контейнер монтируется директория public, а не корневая директория.

Для обновления репозиториев требуется версия PHP не ниже 7.3, с модулями: php_intl, php_mysqli, php_zip

**Для установки необходимо:**
1. Склонировать данный репозиторий в любую директорию
2. Освободить порты 8080 и 33061
3. Выполнить composer update
4. Выполнить sudo docker-compose build
5. Выполнить sudo docker-compose up -d
6. Перейти по адресу http://<ip адрес>:8080/inn/check
