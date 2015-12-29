<?php

$api->version('v1', function ($api) {
    $api->group([
        'prefix'     => 'calendar',
        'namespace'  => $this->namespace.'\api',
        'middleware' => config('society.core.core.middleware.api.backend', []),
        'providers'  => ['jwt'],
    ], function ($api) {

        $api->resource('event', 'EventController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
        $api->resource('preset', 'PresetController', ['only' => ['index', 'store', 'show', 'update', 'destroy']]);
    });
});
