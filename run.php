<?php

declare(strict_types=1);

use Cli\Controller\BootstrapController;

const ROOT_DIR = __DIR__ . '/';

include ROOT_DIR . 'src/Cli/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();