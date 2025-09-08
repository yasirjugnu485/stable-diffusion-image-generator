<?php

namespace App\Controller;

use App\Interface\SettingsInterface;
use App\Model\ConfigModel;
use App\Service\StableDiffusionService;

class SettingsController implements SettingsInterface
{
    /**
     * Host
     *
     * @var string|null
     */
    public static string|null $host = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        if (self::$host !== null) {
            return;
        }

        $configController = new ConfigController();
        $config = $configController->getConfig();
        self::$host = $config['host'];

        $this->handleAction();
    }

    /**
     * Handle action
     *
     * @return void
     */
    private function handleAction(): void
    {
        if (isset($_POST['action']) && $_POST['action'] === 'saveSettings') {
            $this->saveSettings();
        }
    }

    /**
     * Save settings
     *
     * @return void
     */
    private function saveSettings(): void
    {
        if (!isset($_POST['host'])) {
            new ErrorController(self::ERROR_SAVE_SETTINGS);
            $this->redirect();
        }

        $host = $_POST['host'];
        if (!filter_var($host, FILTER_VALIDATE_URL)) {
            new ErrorController(sprintf(self::ERROR_SAVE_SETTINGS_HOST_IS_NOT_AN_VALID_URL, $host));
            $this->redirect();
        }

        $stableDiffusionService = new StableDiffusionService();
        $success = $stableDiffusionService->ping($host);
        if (!$success) {
            new ErrorController(sprintf(self::ERROR_SAVE_SETTINGS_HOST_NOT_REACHABLE, $host));
            $this->redirect();
        }

        $configModel = new ConfigModel();
        $configModel->loadConfigApp();
        $configModel->setHost($host);
        $configModel->buildConfigApp();
        sleep(3);

        new SuccessController(self::SUCCESS_SAVE_SETTINGS);

        $this->redirect();
    }

    /**
     * Redirect
     *
     * @return void
     */
    public function redirect(): void
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Get Settings
     *
     * @return array
     */
    public function getSettings(): array
    {
        return [
            'host' => self::$host,
        ];
    }
}