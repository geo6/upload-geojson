<?php

declare(strict_types=1);

namespace App\Handler;

use Blast\BaseUrl\BaseUrlMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ViewHandler implements RequestHandlerInterface
{
    /** @var string */
    private $containerName;

    /** @var RouterInterface */
    private $router;

    /** @var TemplateRendererInterface */
    private $template;

    public function __construct(
        RouterInterface $router,
        TemplateRendererInterface $template,
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
            $json = json_decode((string) file_get_contents($file['path']));

            $data = [
                'count'         => count($files),
                'current'       => $i,
                'filename'      => basename($file['path']),
                'filesize'      => round((float) filesize($file['path']) / 1024),
                'featuresCount' => isset($json->features) ? count($json->features) : 1,
                'geojson'       => $json,
                'warnings'      => $file['warnings'],
            ];

            return new HtmlResponse($this->template->render('app::view', $data));
        }
    }
}
