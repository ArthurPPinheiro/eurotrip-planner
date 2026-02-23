<?php

$app = require_once __DIR__ . "/../bootstrap/app.php";

$app->useStoragePath("/tmp/storage");

// Create required directories in /tmp
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
