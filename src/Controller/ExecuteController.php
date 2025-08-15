<?php

declare(strict_types=1);

namespace Controller;

use Interface\ExecuteInterface;
use Model\img2imgModel;
use Model\txt2imgModel;
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

            $modelController = new ModelController();
            $modelController->setNextModel();

            if ($config['mode'] === 'txt2img') {
                $promptController = new PromptController();
                $nextPrompt = $promptController->getNextPrompt();
                $this->callTxt2img($nextPrompt);
            } elseif ($config['mode'] === 'img2img') {
                $promptController = new PromptController();
                $nextPrompt = $promptController->getNextPrompt();
                $initImagesController = new InitImagesController();
                $nextInitImages = $initImagesController->getNextInitImages();
                $this->callImg2img($nextPrompt, $nextInitImages);
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

        $modelController = new ModelController();
        $model = $modelController->getCurrentModel();

        $promptController = new PromptController();
        $lastPrompt = $promptController->getLastPrompt();
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $txt2imgModel = new txt2imgModel();
        $txt2imgModel->setPrompt($prompt);
        $payload = $txt2imgModel->toJson();

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
                        $file = base64_decode($image);
                        if (!is_dir('outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $model . '/' .
                            $lastPrompt)) {
                            mkdir('outputs/txt2img/' .
                                $config['dateTime'] . '/' .
                                $model . '/' .
                                $lastPrompt, 0777, true);
                        }
                        file_put_contents('outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $model . '/' .
                            $lastPrompt . '/' .
                            $name . '.png', $file);
                        new EchoController(sprintf(
                            self::SUCCESS_SAVE_IMAGE_WITH_PROMPT,
                            'outputs/txt2img/' .
                            $config['dateTime'] . '/' .
                            $model . '/' .
                            $lastPrompt . '/' .
                            $name . '.png',
                            $prompt
                        ));
                    }
                }
            } else {
                new EchoController(sprintf(self::ERROR_GENERATE_IMAGE_WITH_PROMPT, $prompt));
            }
        }
    }

    private function callImg2img(string $prompt, array $initImages): void
    {
        new EchoController(sprintf(
            self::ERROR_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGES,
            $prompt,
            count($initImages)
        ));

        $modelController = new ModelController();
        $model = $modelController->getCurrentModel();

        $promptController = new PromptController();
        $lastPrompt = $promptController->getLastPrompt();
        $configController = new ConfigController();
        $config = $configController->getConfig();

        $img2imgModel = new img2imgModel();
        $img2imgModel->setPrompt($prompt);
        $img2imgModel->setInitImages($initImages);
        $payload = $img2imgModel->toJson();

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
                        $file = base64_decode($image);
                        if (!is_dir('outputs/img2img/' . 
                            $config['dateTime'] . '/' . 
                            $model . '/' .
                            $lastPrompt)) {
                            mkdir('outputs/img2img/' . 
                                $config['dateTime'] . '/' . 
                                $model . '/' .
                                $lastPrompt);
                        }
                        file_put_contents('outputs/img2img/' . 
                            $config['dateTime'] . '/' . 
                            $model . '/' . 
                            $lastPrompt . '/' . 
                            $name . '.png', $file);
                        new EchoController(sprintf(
                            self::SUCCESS_SAVE_IMAGE_WITH_PROMPT,
                            'outputs/img2img/' . 
                            $config['dateTime'] . '/' . 
                            $model . '/' . 
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
                    count($initImages)
                ));
            }
        }
    }

    private function exit(): void
    {
        $modelController = new ModelController();
        $modelController->restoreStartModel();
    }
}