<?php

use Cezar\Mypostcard\Controller\IndexController;
use Cezar\Mypostcard\Controller\PdfController;
use Cezar\Mypostcard\Controller\ThumbnailController;

require '../vendor/autoload.php';

$request = array_merge($_GET, $_POST);

$response = '';
if (str_starts_with($_SERVER['PATH_INFO'] ?? '', '/thumbnail')) {
    $thumbnailController = new ThumbnailController();

    try {
        $response = $thumbnailController->index($_SERVER);
    } catch (ImagickException $e) {
        print 'Caught exception: ' . $e->getMessage();
    }
}elseif (str_starts_with($_SERVER['PATH_INFO'] ?? '', '/createPdf')) {
    if (!isset($request['id'])) {
        die('Missing id');
    }

    $indexController = new PdfController();
    $indexController->index($request);
} else {
    $indexController = new IndexController();
    $response = $indexController->index($request);
}

print $response;
