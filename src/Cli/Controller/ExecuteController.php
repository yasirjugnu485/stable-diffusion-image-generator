<?php

/**
 * Stable Diffusion Image Generator
 *
 * @author      Moses Rivera
 * @copyright   xtrose® Media Studio 2025
 * @license     GNU GENERAL PUBLIC LICENSE
 */

declare(strict_types=1);

namespace Cli\Controller;

use Cli\Exception\PromptImageGeneratorException;
use Cli\Exception\StableDiffusionServiceException;
use Cli\Interface\ExecuteInterface;
use Cli\Model\Img2ImgModel;
use Cli\Model\Txt2ImgModel;
use Cli\Service\StableDiffusionService;
use Random\RandomException;

class ExecuteController implements ExecuteInterface
{
    /**
     * Execute CLI application
     *
     * @return void
     * @throws PromptImageGeneratorException|StableDiffusionServiceException|RandomException
     */
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

            $promptController = new PromptController();
            $nextPrompt = $promptController->getNextPrompt();
            $nextNegativePrompt = $promptController->getNextNegativePrompt();
            $loraKeywords = $config['loraKeywords'] ?? '';
            $loraController = new LoraController();
            $loraString = $loraController->getLoraString();
            if (($config['mode'] === 'txt2img' && !$config['loop']) ||
                ($config['mode'] === 'txt2img' && $config['loop'] && !$numberOfGeneratedImages)) {
                $this->callTxt2img($nextPrompt . $loraKeywords . $loraString,
                    $nextNegativePrompt,
                    $numberOfGeneratedImages);
            } elseif ($config['mode'] === 'img2img' || (
                    $config['mode'] === 'txt2img' && $config['loop'] && $numberOfGeneratedImages)) {
                $initImagesController = new InitImagesController();
                $nextInitImage = $initImagesController->getNextInitImage();
                $currentInitImageFile = $initImagesController->getCurrentInitImageFile();
                $this->callImg2img($nextPrompt . $loraKeywords . $loraString,
                    $nextNegativePrompt,
                    $nextInitImage,
                    $currentInitImageFile,
                    $numberOfGeneratedImages);
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

    /**
     * Generate txt2txt image
     *
     * @param string $prompt Prompt
     * @param string $negativePrompt Negative prompt
     * @param int $numberOfGeneratedImages Number of generated images
     * @return void
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
    private function callTxt2img(
        string $prompt,
        string $negativePrompt,
        int    $numberOfGeneratedImages
    ): void
    {
        new EchoController(sprintf(self::ECHO_GENERATE_IMAGE_WITH_PROMPT, $prompt, $negativePrompt));

        $configController = new ConfigController();
        $config = $configController->getConfig();

        $txt2imgModel = new Txt2ImgModel();
        $txt2imgModel->setPrompt($prompt);
        $txt2imgModel->setNegativePrompt($negativePrompt);
        $payload = $txt2imgModel->toJson();

        $name = str_pad((string)$numberOfGeneratedImages, 10, '0', STR_PAD_LEFT);
        $directory = ROOT_DIR . 'outputs/';
        if ($config['loop']) {
            $directory .= 'loop/';
        } else {
            $directory .= 'txt2img/';
        }
        $directory .= $config['dateTime'];
        $file = $directory . '/' . $name . '.png';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $dataController = new DataController();
        $dataController->add($file, 'txt2img', $payload);

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
                new EchoController(sprintf(self::ERROR_GENERATE_IMAGE_WITH_PROMPT, $prompt, $negativePrompt));
            }
        } else {
            $this->addDataToDryRun($payload);
        }
    }

    /**
     * Generate img2img image
     *
     * @param string $prompt Prompt
     * @param string $negativePrompt Negative prompt
     * @param string $nextInitImage Next initialize image
     * @param string $currentInitImageFile Current initialize image file
     * @param int $numberOfGeneratedImages Number of generated images
     * @return void
     * @throws PromptImageGeneratorException
     * @throws StableDiffusionServiceException
     */
    private function callImg2img(
        string $prompt,
        string $negativePrompt,
        string $nextInitImage,
        string $currentInitImageFile,
        int    $numberOfGeneratedImages
    ): void
    {
        new EchoController(sprintf(
            self::ECHO_GENERATE_IMAGE_WITH_PROMPT_AND_IMAGE,
            $prompt,
            $negativePrompt,
            $currentInitImageFile
        ));

        $configController = new ConfigController();
        $config = $configController->getConfig();

        $img2imgModel = new Img2ImgModel();
        $img2imgModel->setPrompt($prompt);
        $img2imgModel->setNegativePrompt($negativePrompt);
        $img2imgModel->setInitImages([$nextInitImage]);
        $payload = $img2imgModel->toJson();

        $name = str_pad((string)$numberOfGeneratedImages, 10, '0', STR_PAD_LEFT);
        $directory = ROOT_DIR . 'outputs/';
        if ($config['loop']) {
            $directory .= 'loop/';
        } else {
            $directory .= 'img2img/';
        }
        $directory .= $config['dateTime'];
        $file = $directory . '/' . $name . '.png';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $dataController = new DataController();
        $dataController->add($file, 'img2img', $payload, $currentInitImageFile);

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
                    $negativePrompt,
                    $currentInitImageFile
                ));
            }
        } else {
            $this->addDataToDryRun($payload);
        }
    }

    /**
     * Set next img2img image
     *
     * @param string $nextImage Next image
     * @param string $imageBase64 Image base64
     * @return void
     */
    private function setNextImage(string $nextImage, string $imageBase64): void
    {
        $initImagesController = new InitImagesController();
        $initImagesController->setNextImage($nextImage, $imageBase64);
    }

    /**
     * Add data to dry run
     *
     * @param string $payload Payload
     * @return void
     */
    private function addDataToDryRun(string $payload): void
    {
        $dryRunController = new DryRunController();
        $dryRunController->addData($payload);
    }

    /**
     * Exit CLI application
     *
     * @return void
     */
    private function exit(): void
    {
        $dryRunController = new DryRunController();
        $dryRunController->exit();

        exit();
    }
}