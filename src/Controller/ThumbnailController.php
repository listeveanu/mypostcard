<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Controller;

use Imagick;
use ImagickException;

class ThumbnailController extends BaseController {
    const THUMB_URL = 'https://appdsapi-6aa0.kxcdn.com/card_front_covers/thumb/';
    const WIDTH = 200;

    /**
     * @param $server
     * @return string
     * @throws ImagickException
     */
    public function index($server): string
    {
        $parts = explode('/', $server['PATH_INFO']);
        $fileName = end($parts);

        $imagick = new Imagick(self::THUMB_URL . $fileName . '.jpg');
        $imagick->resizeImage( self::WIDTH, 0, Imagick::FILTER_LANCZOS, 1);
        header("Content-Type: image/png");

        return $imagick->getImageBlob();
    }
}