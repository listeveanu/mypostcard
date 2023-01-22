<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Util;
class View
{
    private string $path = 'template';
    private string $template = 'default';
    private array $_ = [];

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assign(string $key, mixed $value): void
    {
        $this->_[$key] = $value;
    }


    /**
     * @param string $template
     * @return void
     */
    public function setTemplate(string $template = 'default'): void
    {
        $this->template = $template;
    }

    /**
     * @return false|string
     */
    public function loadTemplate(): false|string
    {
        $tpl = $this->template;
        $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . $tpl . '.php';
        $exists = file_exists($file);

        if ($exists) {
            ob_start();

            include $file;
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        } else {
            return 'could not find template';
        }
    }
}
