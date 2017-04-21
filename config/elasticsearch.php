<?php

return [
    'hosts' => [
        env('ELASTIC_HOST', 'localhost').':'.env('ELASTIC_PORT', 9200)
    ]
];