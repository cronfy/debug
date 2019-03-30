<?php

namespace cronfy\debug;

use Symfony\Component\VarDumper\VarDumper;

class Debug {

    public static $debug = false;

    public static function stacktrace($exception) {
        // http://uk3.php.net/manual/en/function.set-exception-handler.php#114582
        return '<div class="alert alert-danger">'
            . '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message '
            . 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>'
            . $exception->getMessage() . '<br>'
            . 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>'
            . '</div>'
        ;
    }

    public static function stacktraceText($exception) {
        return
              'Exception \'' . get_class($exception) . '\' with message '
            . 'thrown in ' . $exception->getFile() . ' on line ' . $exception->getLine() . ' '
            . $exception->getMessage() . ' '
            . 'Stack trace: ' . $exception->getTraceAsString() . ' '
        ;
    }

    /**
     * Дебаг. Печатает $var и делает die().
     *
     * Для глобального использования в виде шортката `D($some_var)`:
     *
     * ```
     * // где-нибудь в глобальном неймспейсе
     * function D($var = null, $vardump = null) { call_user_func_array('\cronfy\debug\Debug::D', [$var, $vardump, 2]); }
     * ```
     *
     * @param mixed $var data to dump
     * @param bool $vardump whether to use var_dump, if false then print_r will be used. Default false.
     * @param int $backtrace_index tuning when called indirectly (e. g. via other debug function)
     */
    public static function D($var, $vardump = false, $backtrace_index = 0)
    {
        if (static::$debug) {
            $backtrace = debug_backtrace();
            $caller = $backtrace[$backtrace_index];
            $file = @$caller['file'];
            $line = @$caller['line'];
            echo "\n<br>\nDebug in {$file} line {$line} (start)\n<br>\n";
            if ($vardump) {
                var_dump($var);
            } else {
                if (class_exists(VarDumper::class)) {
                    VarDumper::dump($var);
                } else {
                    print_r($var);
                }
            }
            echo "\n<br>\nDebug in {$file} line {$line} (end)";
            die();
        } else {
            die('by D');
        }
    }

    /**
     * Дебаг. Печатает $var и продолжает работу.
     *
     * Для глобального использования в виде шортката `E($some_var)`:
     *
     * ```
     * // где-нибудь в глобальном неймспейсе
     * function E($var = null, $vardump = null) { call_user_func_array('\cronfy\debug\Debug::E', [$var, $vardump, 2]); }
     * ```
     *
     * @param mixed $var data to dump
     * @param bool $vardump whether to use var_dump, if false then print_r will be used. Default false.
     * @param int $backtrace_index tuning when called indirectly (e. g. via other debug function)
     */
    public static function E($var, $vardump = false, $backtrace_index = 0)
    {
        if (static::$debug) {
            $backtrace = debug_backtrace();
            $caller = $backtrace[$backtrace_index];
            if ($vardump) {
                var_dump($var);
            } else {
                print_r($var);
            }
            echo " <small>Debug in {$caller['file']} line {$caller['line']}</small><br>\n";
        } else {
            // do not echo
        }
    }

}
