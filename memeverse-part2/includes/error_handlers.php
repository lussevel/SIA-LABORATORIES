<?php

error_reporting(E_ALL);

ini_set(
    'display_errors',
    1
);

set_exception_handler(
function($e){

error_log(
$e->getMessage()
);

http_response_code(500);

echo "<h2>Something went wrong</h2>";
echo "<pre>";
echo $e->getMessage();
echo "</pre>";

}
);

set_error_handler(
function(
$severity,
$message,
$file,
$line
){

throw new ErrorException(
$message,
0,
$severity,
$file,
$line
);

}
);