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

class SuccessController
{
    /**
     * Success messages
     *
     * @var string|null
     */
    public static string|null $success = null;

    /**
     * Constructor
     *
     * @param string|null $message Success message
     * @return void
     */
    public function __construct(string|null $message = null)
    {
        if ($message === null) {
            return;
        }
        if (self::$success === null) {
            self::$success = $message;
        } else {
            self::$success .= '<br>' . $message;
        }
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $_SESSION['SuccessController'] = self::$success;
    }

    /**
     * Get success messages
     *
     * @return string|null
     */
    public function getSuccess(): string|null
    {
        if (isset($_SESSION['SuccessController'])) {
            $error = $_SESSION['SuccessController'];
            unset($_SESSION['SuccessController']);
            return $error;
        }

        return null;
    }
}