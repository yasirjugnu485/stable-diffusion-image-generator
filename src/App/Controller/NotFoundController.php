<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace App\Controller;

class NotFoundController
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $navbarController = new NavbarController;
        $navbar = $navbarController->getData();
        $successController = new SuccessController();
        $success = $successController->getSuccess();
        $errorController = new ErrorController();
        $error = $errorController->getError();

        $this->render([
            'navbar' => $navbar,
            'success' => $success,
            'error' => $error,
        ]);
    }

    /**
     * Render
     *
     * @param array $params Parameters
     * @return void
     */
    private function render(array $params = []): void
    {
        $params['template'] = 'not_found.php';

        $renderController = new RenderController();
        $renderController->render($params);
    }
}