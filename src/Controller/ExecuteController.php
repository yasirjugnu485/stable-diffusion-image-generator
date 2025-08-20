<?php

declare(strict_types=1);

namespace Controller;

use Interface\ExecuteInterface;
use Model\Img2ImgModel;
use Model\Txt2ImgModel;
use Service\StableDiffusionService;

class ExecuteController implements ExecuteInterface
{
    public function run(): void
    {
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $numberOfGeneratedImages = 0;

        while ($numberOfGeneratedImages < $config['numberOfImages']) {
            if ($config['numberOfImages'] > 0) {
                new EchoController(sprintf(
                    self::ECHO_GENERATE_IMAGE_OF,
                    ($numberOfGeneratedImages + 1),
                    $config['numberOfImages']));
            } else {
                new EchoController(sprintf(self::ECHO_GENERATE_IMAGE, ($numberOfGeneratedImages + 1)));
            }

            if (($config['mode'] === 'txt2img' && !$config['loop']) ||
                ($config['mode'] === 'txt2img' && $config['loop'] && !$numberOfGeneratedImages)) {
                $promptController = new PromptController();
                $nextPrompt = $promptController->getNextPrompt();
                $this->callTxt2img($nextPrompt);
            } elseif ($config['mode'] === 'img2img' || (
                $config['mode'] === 'txt2img' && $config['loop'] && $numberOfGeneratedImages)) {
                $promptController = new PromptController();
                $nextPrompt = $promptController->getNextPrompt();
                $initImagesController = new InitImagesController();
                $nextInitImage = $initImagesController->getNextInitImage();
                $currentInitImageFile = $initImagesController->getCurrentInitImageFile();
                $this->callImg2img($nextPrompt, $nextInitImage, $currentInitImageFile);
            }

            new EchoController();

            $numberOfGeneratedImages++;
            if ($config['numberOfImages'] > 0 && $numberOfGeneratedImages >= $config['numberOfImages']) {
                if ($config['saveImages']) {
                    new EchoController(sprintf(self::SUCCESS_SAVE, $numberOfGeneratedImages));
                } else {
                    new EchoController(sprintf(self::SUCCESS_CALL, $numberOfGeneratedImages));
                }
                $this->exit();
            }
        }
    }

    private function callTxt2img(string $prompt): void
    {
        new EchoController(sprintf(self::ECHO_GENERATE_IMAGE_WITH_PROMPT, $prompt));

        $promptController = new PromptController();
        $lastPrompt = $promptController->getLastPrompt();
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $txt2imgModel = new Txt2ImgModel();
        $txt2imgModel->setPrompt($prompt);
        $payload = $txt2imgModel->toJson();

        $checkpointController = new CheckpointController();
        $checkpoint = $checkpointController->getCurrentCheckpoint();

        if (!$config['dryRun']) {
            $stableDiffusionService = new StableDiffusionService();
            $response = $stableDiffusionService->callTxt2img($payload);
            if ($config['saveImages'] && $response) {
                $data = json_decode($response, true);
                if (isset($data['images'])) {
                    foreach ($data['images'] as $image) {
                        $name = microtime();
                        $name = str_replace('.', '_', $name);
                        $name = str_replace(' ', '_', $name);
                        $imageDecoded = base64_decode($image);
                        if (!is_dir('outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $checkpoint . '/' .
                            $lastPrompt)) {
                            mkdir('outputs/txt2img/' .
                                $config['dateTime'] . '/' .
                                $checkpoint . '/' .
                                $lastPrompt, 0777, true);
                        }
                        $file = 'outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $checkpoint . '/' .
                            $lastPrompt . '/' .
                            $name . '.png';
                        file_put_contents($file, $imageDecoded);
                        if ($config['loop']) {
                            $this->setNextImage($file, $image);
                        }
                        new EchoController(sprintf(
                            self::SUCCESS_SAVE_IMAGE_WITH_PROMPT,
                            'outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $checkpoint . '/' .
                            $lastPrompt . '/' .
                            $name . '.png',
                            $prompt
                        ));
                    }
                }
            } else {
                new EchoController(sprintf(self::ERROR_GENERATE_IMAGE_WITH_PROMPT, $prompt));
            }
        } else {
            $this->addPayloadToDryRun($payload);
        }
    }

    private function callImg2img(string $prompt, string $nextInitImage, string $currentInitImageFile): void
    {
        new EchoController(sprintf(
            self::ECHO_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGES,
            $prompt,
            $currentInitImageFile
        ));

        $promptController = new PromptController();
        $lastPrompt = $promptController->getLastPrompt();
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $img2imgModel = new Img2ImgModel();
        $img2imgModel->setPrompt($prompt);
        $img2imgModel->setInitImages([$nextInitImage]);
        $payload = $img2imgModel->toJson();

        $checkpointController = new CheckpointController();
        $checkpoint = $checkpointController->getCurrentCheckpoint();

        if (!$config['dryRun']) {
            $stableDiffusionService = new StableDiffusionService();
            $response = $stableDiffusionService->callImg2img($payload);
            if ($config['saveImages'] && $response) {
                $data = json_decode($response, true);
                if (isset($data['images'])) {
                    foreach ($data['images'] as $image) {
                        $name = microtime();
                        $name = str_replace('.', '_', $name);
                        $name = str_replace(' ', '_', $name);
                        $imageDecoded = base64_decode($image);
                        if (!is_dir('outputs/img2img/' . 
                            $config['dateTime'] . '/' . 
                            $checkpoint . '/' .
                            $lastPrompt)) {
                            mkdir('outputs/img2img/' . 
                                $config['dateTime'] . '/' . 
                                $checkpoint . '/' .
                                $lastPrompt, 0777, true);
                        }
                        $file = 'outputs/img2img/' .
                            $config['dateTime'] . '/' .
                            $checkpoint . '/' .
                            $lastPrompt . '/' .
                            $name . '.png';
                        file_put_contents($file, $imageDecoded);
                        if ($config['loop']) {
                            $this->setNextImage($file, $image);
                        }
                        new EchoController(sprintf(
                            self::SUCCESS_SAVE_IMAGE_WITH_PROMPT,
                            'outputs/img2img/' . 
                            $config['dateTime'] . '/' . 
                            $checkpoint . '/' .
                            $lastPrompt . '/' . 
                            $name . '.png',
                            $prompt
                        ));
                    }
                }
            } else {
                new EchoController(sprintf(
                    self::ERROR_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGES,
                    $prompt,
                    $currentInitImageFile
                ));
            }
        } else {
            $this->addPayloadToDryRun($payload);
        }
    }

    private function setNextImage(string $nextImage, string $imageBase64): void
    {
        $initImagesController = new InitImagesController();
        $initImagesController->setNextImage($nextImage, $imageBase64);
    }

    private function addPayloadToDryRun(string $payload): void
    {
        $dryRunController = new DryRunController();
        $dryRunController->addPayload($payload);
    }

    private function exit(): void
    {
        $dryRunController = new DryRunController();
        $dryRunController->exit();

        exit();
    }
}