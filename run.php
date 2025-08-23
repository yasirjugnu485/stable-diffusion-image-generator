<?php

declare(strict_types=1);

use Cli\Controller\BootstrapController;

include __DIR__ . '/src/Cli/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();