<?php

require_once __DIR__ . '/Helpers/Settings.php';

use Helpers\Settings;

define('SHOW_ROUTE_PATTERN', '/([a-zA-Z0-9]{32})');

$baseUrl = Settings::env('APP_ENV') === 'production' ? 'http://yourappdomain.com' : 'http://localhost:8000';
define('BASE_URL', $baseUrl);