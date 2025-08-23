<?php

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\RefinerInterface;

class RefinerController implements RefinerInterface
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
            $checkpoint = $config['refinerCheckpoint'];
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
                            sprintf(self::ERROR_REFINER_CHECKPOINT_NOT_FOUND, $checkpointName)
                        );
                    }
                }
            }
        }
    }

    private function initCheckpoints(): array
    {
        if (self::$checkpointData === null) {
            $checkpointController = new CheckpointController();
            self::$checkpointData = $checkpointController->getCheckpointData();
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

    public function getCurrentCheckpoint(): string|null
    {
        return self::$currentCheckpoint;
    }
}