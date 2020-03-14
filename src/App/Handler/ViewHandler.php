<?php

declare(strict_types=1);

namespace App\Handler;

use Blast\BaseUrl\BaseUrlMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Session\SessionMiddleware;
use Zend\Expressive\Template;

class ViewHandler implements RequestHandlerInterface
{
    private $containerName;

    private $router;

    private $template;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        string $containerName
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->containerName = $containerName;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $basePath = $request->getAttribute(BaseUrlMiddleware::BASE_PATH);
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $files = $session->get('files');

        $i = intval($request->getAttribute('i'));
        if ($i <= 0) {
            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('view', ['i' => 1]);

            return new RedirectResponse($redirect, 301);
        } elseif ($i > count($files)) {
            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('save');

            return new RedirectResponse($redirect);
        }

        if (!isset($files[$i - 1])) {
            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('home');

            return new RedirectResponse($redirect);
        } else {
            $file = $files[$i - 1];
            $json = json_decode(file_get_contents($file['path']));

            $data = [
                'count'         => count($files),
                'current'       => $i,
                'filename'      => basename($file['path']),
                'filesize'      => round(filesize($file['path']) / 1024),
                'featuresCount' => isset($json->features) ? count($json->features) : 1,
                'geojson'       => $json,
                'warnings'      => $file['warnings'],
            ];

            return new HtmlResponse($this->template->render('app::view', $data));
        }
    }
}
