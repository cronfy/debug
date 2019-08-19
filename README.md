# PHP Debug helpers

Вспомогательные функции для дебага. 

## Как работает

```php
D([1, 2, 'foo', 'bar']);
```

Выведет:

```
Debug in /app/www/index.php line 7 (start) 
Array ( [0] => 1 [1] => 2 [2] => foo [3] => bar ) 
Debug in /app/www/index.php line 7 (end)
```

После чего завершит работу (`die()`). Выводится дамп переменной (`print_r()`), файл, строка, в которой это
произошло.

Можно вывести в формате `var_dump()`:

```php
D([1, 2, 'foo', 'bar'], 1);
```

```
Debug in /app/www/index.php line 7 (start) 
array(4) { [0]=> int(1) [1]=> int(2) [2]=> string(3) "foo" [3]=> string(3) "bar" } 
Debug in /app/www/index.php line 7 (end)
```

Также есть `E()` - делает все то же самое, но не завершает работу после вывода дампа.

## Установка

```
composer require cronfy/debug
```

## Настройка

Чтобы вызывать функцию дебага не через `Debug::D(...)`, а просто `D(...)`, нужна вспомогательная функция в 
глобальном неймспейсе (где-нибудь рядом с подключением `vendor/autoload.php`):

```php
function D($var = null, $vardump = null)
{
    call_user_func_array('\cronfy\debug\Debug::D', [$var, $vardump, (PHP_VERSION_ID >= 70000) ? 1 : 2]);
}
```

По умолчанию функции дебага работают в продакшен-режиме и не выводят никаких данных. Чтобы видеть дамп, 
нужно явно установить режим дебага:

```php
\cronfy\debug\Debug::$debug = true;
```

## Фунции

 * `Debug::D($var)` - дампит `$var` и завершает работу.
 * `Debug::E($var)` - дампит `$var`и продолжает работу.

