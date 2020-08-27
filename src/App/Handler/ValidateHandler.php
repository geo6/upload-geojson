<?php

declare(strict_types=1);

namespace App\Handler;

use App\Validator\GeoJSON as GeoJSONValidator;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Router;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template;

class ValidateHandler implements RequestHandlerInterface
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
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $directory = $session->get('tempDirectory');
        $glob = glob($directory.'/*.*');

        $error = [];
        $warning = [];
        $success = [];
        $files = [];

        foreach ($glob as $file) {
            $fname = basename($file);

            try {
                if (!is_readable($file)) {
                    throw new Exception('Not readable.');
                }

                $mime = mime_content_type($file);
                if (!in_array($mime, [
                    'application/json',
                    'text/plain',
                ])) {
                    throw new Exception(
                        sprintf('Invalid MIME type : %s.', $mime)
                    );
                }

                $json = json_decode(file_get_contents($file));
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON file : '.json_last_error_msg());
                }

                $validator = new GeoJSONValidator(file_get_contents($file));
                if (!$validator->isValid()) {
                    throw new Exception($validator->getError());
                }
                $warnings = $validator->getWarnings();
                if (!empty($warnings)) {
                    $warning[$fname] = array_unique($warnings);
                }

                if (!isset($warning[$fname])) {
                    $success[] = $fname;
                }

                $files[] = [
                    'path'     => $file,
                    'success'  => !isset($warning[$fname]),
                    'warnings' => $warning[$fname] ?? [],
                ];
            } catch (Exception $e) {
                $error[$fname] = $e->getMessage();

                unlink($file);

                continue;
            }
        }

        $glob = glob($directory.'/*.*');
        if (count($glob) === 0) {
            rmdir($directory);
        }

        $session->set('files', $files);

        $data = [
            'count'   => count($glob),
            'error'   => $error,
            'warning' => $warning,
            'success' => $success,
        ];

        return new HtmlResponse($this->template->render('app::validate', $data));
    }
}
