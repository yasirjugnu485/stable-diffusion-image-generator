<?php

declare(strict_types=1);

use App\Controller\BootstrapController;

include_once __DIR__ . '/../src/App/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();