<?php

declare(strict_types=1);

namespace App\Controller;

class NavbarController
{
    private static array|null $navbarData = null;

    public function __construct()
    {
        if (self::$navbarData === null) {
            $this->collectCheckpoints();
            $this->collectNavbarData();
        }
    }

    private function collectCheckpoints(): void
    {
        if (self::$navbarData === null) {
            self::$navbarData = [];
        }

        $fileCollectorController = new FileCollectorController();
        self::$navbarData['checkpoints'] = $fileCollectorController->collectUsedCheckpoints();
    }

    private function collectNavbarData(): void
    {
        if (self::$navbarData === null) {
            self::$navbarData = [];
        }

        $fileCollectorController = new FileCollectorController();
        $fileData = $fileCollectorController->getFileData();
        self::$navbarData['types'] = [];
        foreach ($fileData as $type => $files) {
            self::$navbarData['types'][$type] = [];
            foreach ($files as $key => $file) {
                $entry = str_replace(' ', '_', $key);
                $entry = str_replace(':', '-', $entry);
                self::$navbarData['types'][$type][] = [
                    'name' => $key,
                    'slug' => $entry
                ];
            }
        }
    }

    public function getData(): array
    {
        return self::$navbarData;
    }
}