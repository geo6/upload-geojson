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

class SaveHandler implements RequestHandlerInterface
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

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $basePath = $request->getAttribute(BaseUrlMiddleware::BASE_PATH);
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $method = $request->getMethod();
        $params = $request->getParsedBody();

        if ($method === 'POST' && isset($params['files'])) {
            $session->set('POST:files', $params['files']);

            return new RedirectResponse($basePath.$request->getUri(), 303);
        }

        $tempDirectory = $session->get('tempDirectory');
        $files = $session->get('POST:files');

        if (file_exists($tempDirectory) && is_dir($tempDirectory)) {
            $uploadedFiles = [];
            $skippedFiles = [];

            $directory = 'data/upload/'.date('Ymd-His');
            if (!file_exists($directory) || !is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            foreach ($files as $filename) {
                if (file_exists($tempDirectory.'/'.$filename)) {
                    $uploadedFiles[] = $filename;

                    rename($tempDirectory.'/'.$filename, $directory.'/'.$filename);
                }
            }

            $glob = glob($tempDirectory.'/*.*');
            foreach ($glob as $file) {
                $skippedFiles[] = basename($file);

                unlink($file);
            }
            rmdir($tempDirectory);

            $session->unset('POST:files');

            $data = [
                'directory' => basename($directory),
                'upload'    => $uploadedFiles,
                'skip'      => $skippedFiles,
            ];

            return new HtmlResponse($this->template->render('app::save', $data));
        } else {
            $redirect = $this->router->generateUri('home');
            if ($basePath !== $redirect) {
                $redirect = $basePath.$redirect;
            }

            return new RedirectResponse($redirect);
        }
    }
}
