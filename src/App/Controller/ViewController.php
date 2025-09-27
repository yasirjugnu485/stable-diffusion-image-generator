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

class ViewController
{
    /**
     * View
     *
     * @var string|null
     */
    private static string|null $view = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->handleActions();
        $this->collectView();
    }

    /**
     * Handle actions
     *
     * @return void
     */
    private function handleActions(): void
    {
        if (null === self::$view) {
            if (isset($_POST['action']) && $_POST['action'] === 'changeView') {
                $this->changeView();
            }
        }
    }

    /**
     * Collect view
     *
     * @return void
     */
    private function collectView(): void
    {
        if (null === self::$view) {
            if (isset($_SESSION['view'])) {
                self::$view = $_SESSION['view'];
            } else {
                self::$view = 'list';
            }
        }
    }

    /**
     * Change view
     *
     * @return void
     */
    private function changeView(): void
    {
        if (isset($_POST['view'])) {
            if ($_POST['view'] === 'list' || $_POST['view'] === 'thumbnails') {
                $_SESSION['view'] = $_POST['view'];
                new RedirectController();
            }
        }
    }

    /**
     * Get view
     *
     * @return string
     */
    public function getView(): string
    {
        return self::$view;
    }
}