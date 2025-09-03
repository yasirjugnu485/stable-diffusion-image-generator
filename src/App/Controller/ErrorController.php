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

class ErrorController
{
    /**
     * Error messages
     *
     * @var string|null
     */
    public static string|null $error = null;

    /**
     * Constructor
     *
     * @param string|null $message Error message
     * @return void
     */
    public function __construct(string|null $message = null)
    {
        if ($message === null) {
            return;
        }
        if (self::$error === null) {
            self::$error = $message;
        } else {
            self::$error .= '<br>' . $message;
        }
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $_SESSION['ErrorController'] = self::$error;
    }

    /**
     * Get error messages
     *
     * @return string|null
     */
    public function getError(): string|null
    {
        if (isset($_SESSION['ErrorController'])) {
            $error = $_SESSION['ErrorController'];
            unset($_SESSION['ErrorController']);
            return $error;
        }

        return null;
    }
}