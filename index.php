<?php

require_once 'includes/config.php';

$route = $_GET['route'] ?? 'login';

// Routing
switch ($route) {
    case 'login':
        require_once 'pages/login.php';
        break;

    default:
        http_response_code(404);
        include 'pages/404.php';
        break;
}
