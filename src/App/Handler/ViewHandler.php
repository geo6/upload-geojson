<?php

declare(strict_types=1);

namespace App\Handler;

use App\Validator\GeoJSON as GeoJSONValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Session\SessionMiddleware;

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
        $this->router        = $router;
        $this->template      = $template;
        $this->containerName = $containerName;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $files = $session->get('files');

        $data = [
            'files' => [],
        ];
        foreach ($files as $file) {
            $file['filename'] = basename($file['path']);
            $file['content'] = json_encode(json_decode(file_get_contents($file['path'])));

            $data['files'][] = $file;
        }

        return new HtmlResponse($this->template->render('app::view', $data));
    }
}
