# Laravel Unisender Package

Полноценная интеграция Unisender API для Laravel 12 с поддержкой всех основных методов API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-unisender/unisender.svg?style=flat-square)](https://packagist.org/packages/laravel-unisender/unisender)
[![Tests](https://img.shields.io/github/actions/workflow/status/laravel-unisender/unisender/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravel-unisender/unisender/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-unisender/unisender.svg?style=flat-square)](https://packagist.org/packages/laravel-unisender/unisender)

## Возможности

- ✅ Отправка SMS и email
- ✅ Управление списками контактов
- ✅ Импорт и экспорт контактов
- ✅ Создание и управление кампаниями
- ✅ Управление пользовательскими полями
- ✅ Валидация отправителей
- ✅ Artisan команды для CLI
- ✅ REST API контроллеры
- ✅ Facade для удобного использования
- ✅ Подробное логирование
- ✅ Обработка ошибок
- ✅ Конфигурация через .env

## Установка

### Через Composer

```bash
composer require laravel-unisender/unisender
```

### Публикация конфигурации

```bash
php artisan vendor:publish --provider="LaravelUnisender\UnisenderServiceProvider" --tag="unisender-config"
```

### Настройка переменных окружения

Добавьте в файл `.env`:

```env
# Unisender API Configuration
UNISENDER_API_KEY=your_api_key_here
UNISENDER_ENCODING=UTF-8
UNISENDER_RETRY_COUNT=4
UNISENDER_TIMEOUT=null
UNISENDER_COMPRESSION=false
UNISENDER_PLATFORM="Laravel Unisender Service"
UNISENDER_LANG=en

# Default Settings
UNISENDER_DEFAULT_SMS_SENDER=YourSMSName
UNISENDER_DEFAULT_EMAIL_SENDER=noreply@yourdomain.com
UNISENDER_DEFAULT_LIST_ID=1

# Logging
UNISENDER_ENABLE_LOGGING=true
UNISENDER_LOG_LEVEL=info

# Cache
UNISENDER_ENABLE_CACHE=false
UNISENDER_CACHE_TTL=3600

# Rate Limiting
UNISENDER_ENABLE_RATE_LIMITING=false
UNISENDER_RATE_LIMIT_PER_MINUTE=60

# Webhooks
UNISENDER_WEBHOOK_URL=https://yourdomain.com/webhooks/unisender
UNISENDER_WEBHOOK_SECRET=your_webhook_secret
```

**Важно:** Замените `your_api_key_here` на ваш реальный API ключ от Unisender.

## Использование

### Через Service

```php
use LaravelUnisender\Services\UnisenderService;

class YourController extends Controller
{
    public function sendSms(UnisenderService $unisender)
    {
        $response = $unisender->sendSms([
            'phone' => '+380971234567',
            'text' => 'Hello from Laravel!',
            'sender' => 'MyApp'
        ]);

        if ($unisender->isSuccess($response)) {
            return 'SMS sent successfully!';
        } else {
            return 'Error: ' . $unisender->getErrorMessage($response);
        }
    }
}
```

### Через Facade

```php
use LaravelUnisender\Facades\Unisender;

// Отправка SMS
$response = Unisender::sendSms([
    'phone' => '+380971234567',
    'text' => 'Hello from Laravel!',
    'sender' => 'MyApp'
]);

// Отправка email
$response = Unisender::sendEmail([
    'email' => 'user@example.com',
    'subject' => 'Welcome!',
    'body' => 'Welcome to our service!',
    'sender' => 'noreply@yourdomain.com'
]);

// Получение списков
$lists = Unisender::getLists();

// Подписка контакта
$response = Unisender::subscribe([
    'email' => 'user@example.com',
    'list_ids' => '1,2,3'
]);
```

### Через Dependency Injection

```php
public function sendNotification(UnisenderService $unisender)
{
    $response = $unisender->sendEmail([
        'email' => 'user@example.com',
        'subject' => 'Notification',
        'body_html' => '<h1>Hello!</h1><p>This is a notification.</p>',
        'sender' => 'noreply@yourdomain.com'
    ]);

    return $response;
}
```

## Artisan Команды

### Отправка SMS

```bash
php artisan unisender:sms "+380971234567" "Hello from Laravel!" --sender="MyApp"
```

### Отправка Email

```bash
php artisan unisender:email "user@example.com" "Welcome!" --body="Welcome to our service!" --sender="noreply@yourdomain.com"
```

### Получение списков контактов

```bash
php artisan unisender:lists
php artisan unisender:lists --format=json
```

## REST API

После установки пакета автоматически регистрируются следующие маршруты:

### Отправка SMS
```http
POST /api/unisender/sms
Content-Type: application/json

{
    "phone": "+380971234567",
    "text": "Hello from API!",
    "sender": "MyApp"
}
```

### Отправка Email
```http
POST /api/unisender/email
Content-Type: application/json

{
    "email": "user@example.com",
    "subject": "Welcome!",
    "body": "Welcome to our service!",
    "sender": "noreply@yourdomain.com"
}
```

### Получение списков
```http
GET /api/unisender/lists
```

### Создание списка
```http
POST /api/unisender/lists
Content-Type: application/json

{
    "title": "New List",
    "description": "Description of the list"
}
```

### Подписка контакта
```http
POST /api/unisender/subscribe
Content-Type: application/json

{
    "email": "user@example.com",
    "list_ids": "1,2,3",
    "tags": "vip,customer"
}
```

## Доступные методы

### SMS и Email
- `sendSms(array $params)` - Отправка SMS
- `sendEmail(array $params)` - Отправка email
- `createEmailMessage(array $params)` - Создание email сообщения
- `createSmsMessage(array $params)` - Создание SMS сообщения

### Списки контактов
- `getLists()` - Получение всех списков
- `createList(array $params)` - Создание списка
- `updateList(array $params)` - Обновление списка
- `deleteList(array $params)` - Удаление списка

### Контакты
- `subscribe(array $params)` - Подписка контакта
- `unsubscribe(array $params)` - Отписка контакта
- `exclude(array $params)` - Исключение контакта
- `importContacts(array $params)` - Импорт контактов
- `getContact(array $params)` - Получение информации о контакте
- `getContactFieldValues(array $params)` - Получение значений полей контакта
- `isContactInLists(array $params)` - Проверка наличия контакта в списках

### Кампании
- `createCampaign(array $params)` - Создание кампании
- `getCampaigns(array $params = [])` - Получение кампаний
- `getCampaignStatus(array $params)` - Получение статуса кампании

### Поля и теги
- `getFields()` - Получение пользовательских полей
- `createField(array $params)` - Создание поля
- `updateField(array $params)` - Обновление поля
- `deleteField(array $params)` - Удаление поля
- `getTags()` - Получение тегов
- `deleteTag(array $params)` - Удаление тега

### Асинхронные операции
- `taskExportContacts(array $params)` - Экспорт контактов
- `getTaskResult(array $params)` - Получение результата задачи

### Дополнительные методы
- `getCurrencyRates()` - Получение курсов валют
- `validateSender(array $params)` - Валидация отправителя
- `setSenderDomain(array $params)` - Установка домена отправителя
- `getSenderDomainList(array $params = [])` - Получение списка доменов
- `getCheckedEmail(array $params = [])` - Получение проверенных email

## Обработка ошибок

```php
try {
    $response = $unisender->sendSms($params);
    
    if ($unisender->isSuccess($response)) {
        // Успешная операция
        $result = $response['result'];
    } else {
        // Ошибка API
        $errorMessage = $unisender->getErrorMessage($response);
        Log::error('Unisender API error: ' . $errorMessage);
    }
} catch (\Exception $e) {
    // Исключение (сетевая ошибка, неверный API ключ и т.д.)
    Log::error('Unisender exception: ' . $e->getMessage());
}
```

## Логирование

Сервис автоматически логирует все API запросы и ошибки. Логи можно найти в `storage/logs/laravel.log`.

## Кэширование

Для включения кэширования API ответов установите в `.env`:

```env
UNISENDER_ENABLE_CACHE=true
UNISENDER_CACHE_TTL=3600
```

## Rate Limiting

Для включения ограничения запросов установите в `.env`:

```env
UNISENDER_ENABLE_RATE_LIMITING=true
UNISENDER_RATE_LIMIT_PER_MINUTE=60
```

## Тестирование

```bash
composer test
```

## Поддержка

Для получения поддержки по Unisender API обратитесь к официальной документации:
- [Unisender API Documentation](https://www.unisender.com/en/support/category/api/)
- [Unisender API Documentation (RU)](https://www.unisender.com/ru/support/category/api/)

## Вклад в проект

1. Fork репозиторий
2. Создайте ветку для новой функции (`git checkout -b feature/amazing-feature`)
3. Зафиксируйте изменения (`git commit -m 'Add some amazing feature'`)
4. Отправьте в ветку (`git push origin feature/amazing-feature`)
5. Откройте Pull Request

## Лицензия

Этот пакет распространяется под лицензией MIT. См. файл [LICENSE](LICENSE) для получения дополнительной информации. 