<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\CheckpointInterface;
use Cli\Service\StableDiffusionService;

class CheckpointController implements CheckpointInterface
{
    private static array|null $checkpointData = null;

    private static array|false|null $checkpoint = false;

    private static string|null $lastCheckpoint = null;

    private static string|null $currentCheckpoint = null;

    public function __construct()
    {
        if (self::$checkpointData === null) {
            $checkpoints = $this->initCheckpoints();
            if (!$checkpoints) {
                throw new StableDiffusionServiceException(self::ERROR_NO_CHECKPOINTS_FOUND);
            }

            $configController = new ConfigController();
            $config = $configController->getConfig();
            $checkpoint = $config['checkpoint'];
            if ($checkpoint !== false) {
                if (is_array($checkpoint)) {
                    self::$checkpoint = $checkpoint;
                } elseif (is_string($checkpoint)) {
                    self::$checkpoint = [$checkpoint];
                } elseif (is_null($checkpoint)) {
                    self::$checkpoint = [];
                    foreach ($checkpoints as $checkpoint) {
                        self::$checkpoint[] = $checkpoint['model_name'];
                    }
                }

                if (is_array(self::$checkpoint)) {
                    foreach (self::$checkpoint as $checkpointName) {
                        foreach ($checkpoints as $checkpoint) {
                            if ($checkpointName === $checkpoint['model_name']) {
                                continue 2;
                            }
                        }
                        throw new StableDiffusionServiceException(
                            sprintf(self::ERROR_CHECKPOINT_NOT_FOUND, $checkpointName)
                        );
                    }
                }
            }
        }
    }

    private function initCheckpoints(bool $silent = false): array
    {
        if (self::$checkpointData === null) {
            if (!$silent) {
                new EchoController(self::ECHO_INIT_CHECKPOINTS);
            }

            $stableDiffusionService = new StableDiffusionService();
            self::$checkpointData = $stableDiffusionService->getSdModels();

            if (!$silent) {
                new EchoController(self::SUCCESS_INIT_CHECKPOINTS);
                new EchoController();
            }
        }

        return self::$checkpointData;
    }

    public function setNextCheckpoint(): void
    {
        if (is_array(self::$checkpoint)) {
            if (self::$currentCheckpoint === null) {
                self::$currentCheckpoint = self::$checkpoint[0];
            } else {
                self::$lastCheckpoint = self::$currentCheckpoint;

                self::$currentCheckpoint = null;
                $next = false;
                foreach (self::$checkpoint as $checkpoint) {
                    if ($next) {
                        self::$currentCheckpoint = $checkpoint;
                        break;
                    } elseif ($checkpoint === self::$lastCheckpoint) {
                        $next = true;
                    }
                }
                if (self::$currentCheckpoint === null) {
                    self::$currentCheckpoint = self::$checkpoint[0];
                }
            }
        }
    }

    public function getCheckpointData(): array|null
    {
        return self::$checkpointData;
    }

    public function getCurrentCheckpoint(): string|null
    {
        return self::$currentCheckpoint;
    }
}