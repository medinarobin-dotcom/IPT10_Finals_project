<?php

session_start();

// Load configuration
$config = require __DIR__ . '/../config/app.php';

// Load core classes
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Database.php';

// Load middleware
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Middleware/LoggingMiddleware.php';

// Load controllers
require_once __DIR__ . '/../app/Controllers/HomeController.php';

// Initialize framework
$request = new Request();
$router = new Router();
$home = new HomeController();

// Load application routes
require_once __DIR__ . '/../routes/web.php';

// Handle request
$router->dispatch($request);