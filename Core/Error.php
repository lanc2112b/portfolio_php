<?php

namespace Core;

use Core\ViewJSON;

/** Error Handler - f #16 */
class Error
{
    /**
     * errorHandler
     *
     * @param [type] $level
     * @param [type] $message
     * @param [type] $file
     * @param [type] $line
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * exceptionHandler
     *
     * @param object $exception
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        $status = $exception->getCode() ?? 500;
        /** don't just need 404 or 500, as rest, needs to return correct codes */
        //if ($code != 404)
        
        $error_details = [
            'in' => get_class($exception),
            'msg' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'show_errors' => $_ENV['SHOW_ERRORS']
        ];

        $show_err = filter_var(getenv('SHOW_ERRORS'), FILTER_VALIDATE_BOOLEAN);

        if ($show_err) {
            //var_dump($show_err);
            ViewJSON::responseJson($error_details, $status);
        } else {

            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);
            error_log(json_encode($error_details));
            ViewJSON::responseJson(['msg' => $exception->getMessage()], $status);
        }
    }
}
