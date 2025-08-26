<?php

declare(strict_types = 1);

const ROOT_DIR = __DIR__ . '/../';

if (!isset($_GET['image'])) {
    header("HTTP/1.0 404 Not Found");
    exit();
} elseif (!file_exists(ROOT_DIR . $_GET['image'])) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

header("Content-type: image/jpeg");
readfile(ROOT_DIR . $_GET['image']);