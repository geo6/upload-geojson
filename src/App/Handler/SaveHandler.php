<?php

declare(strict_types=1);

namespace App\Handler;

use App\Validator\GeoJSON as GeoJSONValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Session\SessionMiddleware;

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
        $this->router        = $router;
        $this->template      = $template;
        $this->containerName = $containerName;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $tempDirectory = $session->get('tempDirectory');
        if (is_null($tempDirectory)) {
            return new RedirectResponse($this->router->generateUri('home'));
        }

        $params = $request->getParsedBody();

        if (isset($params['files'])) {
            $directory = 'data/upload/'.date('Ymd-His');

            if (!file_exists($directory) || !is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $files = [];
            foreach ($params['files'] as $filename) {
                if (file_exists($tempDirectory.'/'.$filename)) {
                    $files[] = $filename;

                    rename($tempDirectory.'/'.$filename, $directory.'/'.$filename);
                }
            }

            $skipped = [];
            $glob = glob($tempDirectory.'/*.*');
            foreach ($glob as $file) {
                $skipped[] = basename($file);

                unlink($file);
            }
            rmdir($tempDirectory);

            $session->unset('tempDirectory');

            $data = [
                'directory' => basename($directory),
                'files' => $files,
                'skipped' => $skipped,
            ];

            return new HtmlResponse($this->template->render('app::save', $data));
        }
    }
}
