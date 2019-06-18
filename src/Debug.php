<?php

namespace cronfy\debug;

use Symfony\Component\VarDumper\VarDumper;

class Debug {

    const DUMP_PRINT_R = 2;
    const DUMP_PRINT_R_ALIAS = 0;
    const DUMP_VAR_DUMP = 3;
    const DUMP_SIMFONY_VAR_DUMPER = 4;

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
     * Debug. Prints $var and die()s.
     *
     * To create a shortcut like `D($some_var);`, define function like this somewhere in global namespace:
     *
     * ```
     * // somewhere in global namespace
     *
     * function D($var = null, $format = null) { call_user_func_array('\cronfy\debug\Debug::D', [$var, $format, 2]); }
     * ```
     *
     * @param mixed $var data to dump
     * @param bool $dumpFormat dump format, @see Debug::dumpVar(). Default is print_r().
     * @param int $backtrace_index tuning when called indirectly (e. g. via other debug function)
     */
    public static function D($var, $dumpFormat = false, $backtrace_index = 0)
    {
        if (static::$debug) {
            $backtrace = debug_backtrace();
            $caller = $backtrace[$backtrace_index];
            $file = @$caller['file'];
            $line = @$caller['line'];
            echo "\n<br>\nDebug in {$file} line {$line} (start)\n<br>\n";

            static::dumpVar($var, $dumpFormat);

            echo "\n<br>\nDebug in {$file} line {$line} (end)";
            die();
        } else {
            die('by D');
        }
    }

    protected static function dumpVar($var, $format) {
        switch (true) {
            case $format === static::DUMP_PRINT_R:
            case $format === static::DUMP_PRINT_R_ALIAS:
                break;
            case $format === static::DUMP_SIMFONY_VAR_DUMPER:
                break;
            case $format === static::DUMP_VAR_DUMP:
                break;
            case $format == true:
                $format = static::DUMP_VAR_DUMP;
                break;
            default:
                if (class_exists(VarDumper::class)) {
                    $format = static::DUMP_SIMFONY_VAR_DUMPER;
                } else {
                    $format = static::DUMP_PRINT_R;
                }
                break;
        }

        switch ($format) {
            case static::DUMP_VAR_DUMP:
                var_dump($var);
                break;
            case static::DUMP_SIMFONY_VAR_DUMPER:
                VarDumper::dump($var);
                break;
            case static::DUMP_PRINT_R:
            default:
                print_r($var);
                break;
        }
    }

    /**
     * Echo debug. Prints $var, but does not call die().
     *
     * To create a shortcut like `E($some_var);`, define function like this somewhere in global namespace:
     *
     * ```
     * // somewhere in global namespace
     *
     * function E($var = null, $format = null) { call_user_func_array('\cronfy\debug\Debug::E', [$var, $format, 2]); }
     * ```
     *
     * @param mixed $var data to dump
     * @param bool $dumpFormat dump format
     * @param int $backtrace_index tuning when called indirectly (e. g. via other debug function)
     */
    public static function E($var, $dumpFormat = false, $backtrace_index = 0)
    {
        if (static::$debug) {
            $backtrace = debug_backtrace();
            $caller = $backtrace[$backtrace_index];

            static::dumpVar($var, $dumpFormat);

             echo " <small>Debug in {$caller['file']} line {$caller['line']}</small><br>\n";
        } else {
            // do not echo
        }
    }

}
