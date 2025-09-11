<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

use App\Controller\BootstrapController;

const PUBLIC_DIR = __DIR__ . '/';
define ('ROOT_DIR', substr(PUBLIC_DIR, 0, strlen(PUBLIC_DIR) - 7));

include_once ROOT_DIR . 'src/App/Controller/BootstrapController.php';
$bootstrapController = new BootstrapController();
$bootstrapController->run();