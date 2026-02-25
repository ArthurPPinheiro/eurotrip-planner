<?php

$root = __DIR__ . "/..";

// Tell Laravel the request is HTTPS before it captures the request
$_SERVER["HTTPS"] = "on";
$_SERVER["SERVER_PORT"] = 443;

require_once $root . "/vendor/autoload.php";

$app = require_once $root . "/bootstrap/app.php";

$app->useStoragePath("/tmp/storage");

$dirs = [
    "/tmp/storage/framework/views",
    "/tmp/storage/framework/cache",
    "/tmp/storage/framework/sessions",
    "/tmp/storage/logs",
    "/tmp/storage/app",
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
