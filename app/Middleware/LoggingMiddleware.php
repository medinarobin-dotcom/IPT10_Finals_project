<?php

class LoggingMiddleware
{
    public function handle($request)
    {
        $log = date('Y-m-d H:i:s') . " - "
             . $request->method() . " "
             . $request->path() . PHP_EOL;

        file_put_contents(
            __DIR__ . '/../../requests.log',
            $log,
            FILE_APPEND
        );

        return true;
    }
}