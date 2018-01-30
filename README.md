# PHP Debug helpers

## Debug::stacktrace()

Выводит полный stacktrace для Exception PHP.

Пример использования:

```php
define('YII_ENABLE_ERROR_HANDLER', false);
set_exception_handler([
    'cronfy\experience\php\debug\Debug',
    'stacktrace'
]);
```

## D(), E()

Инструкции в исходниках.