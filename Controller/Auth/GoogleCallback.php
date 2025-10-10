<?php
require_once __DIR__ . '/../../lib/google-api-php-client-v2.18.3-PHP8.3/vendor/autoload.php';

require_once 'GoogleAuthController.php';

$controller = new GoogleAuthController();
$controller->callback();