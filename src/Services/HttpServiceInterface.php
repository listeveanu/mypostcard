<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Services;

interface HttpServiceInterface {
    public function request(string $url, string $method='GET', array $headers=[]);
}