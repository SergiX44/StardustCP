<?php

namespace Modules\Web;


use Core\Modules\BaseModule;
use function Safe\file_get_contents;
use function Safe\json_decode;

class WebModule extends BaseModule
{
    /**
     * @return string
     */
    public function name()
    {
        return 'web';
    }

    /**
     * @return null|string
     */
    public function fancyName()
    {
        return 'Web Module';
    }

    public function icon()
    {
        return 'fa-globe';
    }

    /**
     * @return null|string
     */
    public function description()
    {
        return 'The web module.';
    }

    /**
     * @return bool
     */
    public function hasConfig()
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function configRoute()
    {
        return route('web.configure');
    }

    /**
     * @return mixed
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\JsonException
     */
    public function version()
    {
        return json_decode(file_get_contents(__DIR__.'/../composer.json'))->version;
    }
}
