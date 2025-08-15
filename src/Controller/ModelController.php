<?php

declare(strict_types=1);

namespace Controller;

use Exception\StableDiffusionServiceException;
use Interface\ModelInterface;
use Service\StableDiffusionService;

class ModelController implements ModelInterface
{
    private static array|null $modelData = null;

    private static array|false|null $model = false;

    private static string|null $startModel = null;
    
    private static string|null $lastModel = null;

    private static string|null $currentModel = null;

    public function __construct()
    {
        if (self::$modelData === null) {
            $models = $this->initModels();
            if (!$models) {
                throw new StableDiffusionServiceException(self::ERROR_NO_MODELS_FOUND);
            }

            $configController = new ConfigController();
            $config = $configController->getConfig();
            $model = $config['model'];
            if ($model !== false) {
                if (is_array($model)) {
                    self::$model = $model;
                } elseif (is_string($model)) {
                    self::$model = [$model];
                } elseif (is_null($model)) {
                    self::$model = [];
                    foreach ($models as $model) {
                        self::$model[] = $model['model_name'];
                    }
                }

                if (is_array(self::$model)) {
                    foreach (self::$model as $modelName) {
                        foreach ($models as $model) {
                            if ($modelName === $model['model_name']) {
                                continue 2;
                            }
                        }
                        throw new StableDiffusionServiceException(sprintf(self::ERROR_MODEL_NOT_FOUND, $modelName));
                    }
                }

                $this->getStartModel();
            }
        }
    }

    private function initModels(bool $silent = false): array
    {
        if (self::$modelData === null) {
            if (!$silent) {
                new EchoController(self::ECHO_INIT_MODELS);
            }

            $stableDiffusionService = new StableDiffusionService();
            self::$modelData = $stableDiffusionService->getSdModels();

            if (!$silent) {
                new EchoController(self::SUCCESS_INIT_MODELS);
                new EchoController();
            }
        }

        return self::$modelData;
    }

    public function setNextModel(): void
    {
        if (is_array(self::$model)) {
            if (self::$currentModel === null) {
                self::$currentModel = self::$model[0];
            } else {
                self::$lastModel = self::$currentModel;

                self::$currentModel = null;
                $next = false;
                foreach (self::$model as $model) {
                    if ($next) {
                        self::$currentModel = $model;
                        break;
                    } elseif ($model === self::$lastModel) {
                        $next = true;
                    }
                }
                if (self::$currentModel === null) {
                    self::$currentModel = self::$model[0];
                }
            }

            $optionController = new OptionController();
            $optionController->setOptions(['sd_model_checkpoint' => self::$currentModel]);
        }
    }

    private function getStartModel(): void
    {
        new EchoController(self::ECHO_GET_START_MODEL);

        self::$startModel = $this->getModel();

        new EchoController(sprintf(self::SUCCESS_GET_START_MODEL, self::$startModel));
        new EchoController();
    }

    public function restoreStartModel(): void
    {
        if (self::$startModel !== null) {
            new EchoController(sprintf(self::ECHO_RESTORE_START_MODEL, self::$startModel));

            $optionController = new OptionController();
            $optionController->setOptions(['sd_model_checkpoint' => self::$startModel]);

            new EchoController(sprintf(self::SUCCESS_RESTORE_START_MODEL, self::$startModel));
            new EchoController();
        }
    }

    public function getModels(): array
    {
        return $this->initModels();
    }

    public function getModel(): string
    {
        $optionController = new OptionController();

        return $optionController->getOption('sd_model_checkpoint');
    }

    public function getCurrentModel(): string|null
    {
        return self::$currentModel;
    }

    public function getLastModel(): string|null
    {
        return self::$lastModel;
    }
}