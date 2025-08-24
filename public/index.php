<?php

declare(strict_types=1);

use App\Controller\BootstrapController;

const PUBLIC_DIR = __DIR__ . '/';
const ROOT_DIR = __DIR__ . '/../';

include_once ROOT_DIR . 'src/App/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();