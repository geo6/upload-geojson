<?php

declare(strict_types=1);

namespace App\Handler;

use Blast\BaseUrl\BaseUrlMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Router;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $basePath = $request->getAttribute(BaseUrlMiddleware::BASE_PATH);
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $i = intval($request->getAttribute('i'));

        $directory = $session->get('directory');
        $files = $session->get('files');
        $tempDirectory = $session->get('tempDirectory');
        $uploadedFiles = $session->get('uploadedFiles', []);
        $user = $session->get(UserInterface::class);

        if (is_null($tempDirectory) || !file_exists($tempDirectory) || !is_dir($tempDirectory)) {
            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('home');

            return new RedirectResponse($redirect);
        }

        if (is_null($directory)) {
            if (!is_null($user)) {
                $role = $user['roles'][0];

                $directory = sprintf(
                    'data/upload/%s/%s',
                    $role,
                    date('Ymd-His')
                );
            } else {
                $directory = sprintf(
                    'data/upload/%s',
                    date('Ymd-His')
                );
            }
            if (!file_exists($directory) || !is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $session->set('directory', $directory);
        }

        if ($i > 0 && isset($files[$i - 1])) {
            $file = $files[$i - 1];
            $filename = basename($file['path']);

            copy($tempDirectory.'/'.$filename, $directory.'/'.$filename);

            $uploadedFiles[] = $filename;

            if (($i + 1) <= count($files)) {
                $session->set('uploadedFiles', $uploadedFiles);

                $redirect = ($basePath !== '/' ? $basePath : '');
                $redirect .= $this->router->generateUri('view', ['i' => $i + 1]);

                return new RedirectResponse($redirect);
            }
        }

        $skippedFiles = [];
        $glob = glob($tempDirectory.'/*.*');
        foreach ($glob as $file) {
            $filename = basename($file);

            if (!in_array($filename, $uploadedFiles)) {
                $skippedFiles[] = $filename;
            }

            unlink($file);
        }
        rmdir($tempDirectory);

        $session->unset('directory');
        $session->unset('files');
        $session->unset('tempDirectory');
        $session->unset('uploadedFiles');

        $data = [
            'directory' => substr($directory, 12),
            'upload'    => $uploadedFiles,
            'skip'      => $skippedFiles,
        ];

        return new HtmlResponse($this->template->render('app::save', $data));
    }
}
