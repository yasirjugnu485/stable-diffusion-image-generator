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
                $this->callTxt2img($nextPrompt, $numberOfGeneratedImages);
            } elseif ($config['mode'] === 'img2img' || (
                $config['mode'] === 'txt2img' && $config['loop'] && $numberOfGeneratedImages)) {
                $promptController = new PromptController();
                $nextPrompt = $promptController->getNextPrompt();
                $initImagesController = new InitImagesController();
                $nextInitImage = $initImagesController->getNextInitImage();
                $currentInitImageFile = $initImagesController->getCurrentInitImageFile();
                $this->callImg2img($nextPrompt, $nextInitImage, $currentInitImageFile, $numberOfGeneratedImages);
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

    private function callTxt2img(
        string $prompt,
        int $numberOfGeneratedImages
    ): void
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

        $name = str_pad((string)$numberOfGeneratedImages, 10, '0', STR_PAD_LEFT);
        $directory = 'outputs/';
        if ($config['loop']) {
            $directory .= 'loop/';
        } else {
            $directory .= 'txt2img/';
        }
        $directory .= $config['dateTime'] . '/' . $checkpoint . '/' . $lastPrompt;
        $file = $directory . '/' . $name . '.png';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $payloadController = new PayloadController();
        $payloadController->add($file, 'txt2img', $payload);

        if (!$config['dryRun']) {
            $stableDiffusionService = new StableDiffusionService();
            $response = $stableDiffusionService->callTxt2img($payload);
            if ($config['saveImages'] && $response) {
                $data = json_decode($response, true);
                if (isset($data['images'][0])) {
                    $image = $data['images'][0];
                    $imageDecoded = base64_decode($image);
                    file_put_contents($file, $imageDecoded);
                    if ($config['loop']) {
                        $this->setNextImage($file, $image);
                    }
                    new EchoController(sprintf(self::SUCCESS_SAVE_IMAGE, $file));
                }
            } else {
                new EchoController(sprintf(self::ERROR_GENERATE_IMAGE_WITH_PROMPT, $prompt));
            }
        } else {
            $this->addPayloadToDryRun($payload);
        }
    }

    private function callImg2img(
        string $prompt,
        string $nextInitImage,
        string $currentInitImageFile,
        int $numberOfGeneratedImages
    ): void
    {
        new EchoController(sprintf(
            self::ECHO_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGE,
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

        $name = str_pad((string)$numberOfGeneratedImages, 10, '0', STR_PAD_LEFT);
        $directory = 'outputs/';
        if ($config['loop']) {
            $directory .= 'loop/';
        } else {
            $directory .= 'img2img/';
        }
        $directory .= $config['dateTime'] . '/' . $checkpoint . '/' . $lastPrompt;
        $file = $directory . '/' . $name . '.png';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $payloadController = new PayloadController();
        $payloadController->add($file, 'img2img', $payload, $currentInitImageFile);

        if (!$config['dryRun']) {
            $stableDiffusionService = new StableDiffusionService();
            $response = $stableDiffusionService->callImg2img($payload);
            if ($config['saveImages'] && $response) {
                $data = json_decode($response, true);
                if (isset($data['images'][0])) {
                    $image = $data['images'][0];
                    $imageDecoded = base64_decode($image);
                    file_put_contents($file, $imageDecoded);
                    if ($config['loop']) {
                        $this->setNextImage($file, $image);
                    }
                    new EchoController(sprintf(self::SUCCESS_SAVE_IMAGE, $file));
                }
            } else {
                new EchoController(sprintf(
                    self::ERROR_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGE,
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