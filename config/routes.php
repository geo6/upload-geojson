<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/*
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $loadAuthenticationMiddleware = function ($middleware) use ($container) {
        if (isset($container->get('config')['authentication']['pdo'])) {
            return [
                AuthenticationMiddleware::class,
                $middleware,
            ];
        }

        return $middleware;
    };

    $app->get('/', $loadAuthenticationMiddleware(App\Handler\UploadHandler::class), 'home');
    $app->get('/validate', $loadAuthenticationMiddleware(App\Handler\ValidateHandler::class), 'validate');
    $app->get('/view[/{i:\d+}]', $loadAuthenticationMiddleware(App\Handler\ViewHandler::class), 'view');
    $app->get('/save[/{i:\d+}]', $loadAuthenticationMiddleware(App\Handler\SaveHandler::class), 'save');

    $app->route('/api/upload', App\Handler\API\UploadHandler::class, ['GET', 'POST'], 'api.upload');

    $app->route('/login', [
        App\Handler\LoginHandler::class,
        Mezzio\Authentication\AuthenticationMiddleware::class,
    ], ['GET', 'POST'], 'login');
    $app->get('/logout', $loadAuthenticationMiddleware(App\Handler\LoginHandler::class), 'logout');
};
