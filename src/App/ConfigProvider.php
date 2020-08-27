<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module.
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array.
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies.
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\API\UploadHandler::class => Handler\API\UploadHandler::class,
            ],
            'factories'  => [
                Middleware\UIMiddleware::class => Middleware\UIMiddlewareFactory::class,

                Handler\LoginHandler::class    => Handler\LoginHandlerFactory::class,
                Handler\SaveHandler::class     => Handler\SaveHandlerFactory::class,
                Handler\UploadHandler::class   => Handler\UploadHandlerFactory::class,
                Handler\ValidateHandler::class => Handler\ValidateHandlerFactory::class,
                Handler\ViewHandler::class     => Handler\ViewHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration.
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
