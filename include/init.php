<?php

$ROOT = __DIR__ . '/../';

require_once "$ROOT/config.php";

spl_autoload_register(function ($class) use ($ROOT) {
    require_once $ROOT . "/class/{$class}.php";
});

session_start();

function errorHandler($level, $message, $file, $line)
{
    throw new ErrorException($message, 0, $level, $file, $line);
}

function exceptionHandler($exception)
{
    http_response_code(500);

    echo '<h1>En error occurred:</h1>';
    echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
    echo '<p>' . $exception->getMessage() . '</p>';
    echo '<p>Stack trace: <pre>' . $exception->getTraceAsString() . '</pre></p>';
    echo '<p>In file ' . $exception->getFile() . ' on line ' . $exception->getLine() . '</p>';

    exit;
}

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');
