<?php

declare(strict_types=1);

use Controller\BootstrapController;

include 'src/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();