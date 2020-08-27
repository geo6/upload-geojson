<?php

declare(strict_types=1);

namespace App\Handler;

// use App\Log;
use Blast\BaseUrl\BaseUrlMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Log\Logger;
use Mezzio\Authentication\UserInterface;
use Mezzio\Router;
use Mezzio\Router\RouteResult;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Template;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements MiddlewareInterface
{
    private $authentication;

    private $containerName;

    private $router;

    private $template;

    public function __construct(
        Router\RouterInterface $router,
        Template\TemplateRendererInterface $template = null,
        string $containerName,
        bool $authentication
    ) {
        $this->router = $router;
        $this->template = $template;
        $this->containerName = $containerName;
        $this->authentication = $authentication;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RouteResult::class);
        $basePath = $request->getAttribute(BaseUrlMiddleware::BASE_PATH);

        if ($this->authentication === false) {
            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('home');

            return new RedirectResponse($redirect);
        }

        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        if ($session->has(UserInterface::class)) {
            if ($route->getMatchedRouteName() === 'logout') {
                $session->clear();
            }

            $redirect = ($basePath !== '/' ? $basePath : '');
            $redirect .= $this->router->generateUri('home');

            return new RedirectResponse($redirect);
        }

        $error = '';
        if ($request->getMethod() === 'POST') {
            $response = $handler->handle($request);

            if ($response->getStatusCode() !== 302) {
                $user = $session->get(UserInterface::class);

                // (new Log())->write('User "{username}" logged in.', ['username' => $user['username']], Logger::INFO);

                $redirect = ($basePath !== '/' ? $basePath : '');
                $redirect .= $this->router->generateUri('home');

                return new RedirectResponse($redirect);
            }

            // (new Log())->write('User "{username}" failed to log in.', ['username' => $_POST['username']], Logger::WARN);

            $error = 'Login failure, please try again.';
        }

        $data = [
            'error' => $error,
        ];

        return new HtmlResponse($this->template->render('app::login', $data));
    }
}
