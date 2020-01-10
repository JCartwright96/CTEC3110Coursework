<?php
declare(strict_types=1);

namespace M2m\Middleware;

use function Composer\Autoload\includeFile;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ArrayAccess;

class SessionMiddleware implements Middleware
{
    private $storage;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
            $this->storage =& $_SESSION;
        }
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!isset($this->storage['logged'])) {
            $this->storage['logged'] = false;
        }

        $request = $request->withAttribute('session', $this);
        $request = $request->withAttribute('user_info', array_key_exists('user_info', $this->storage) ? $this->storage['user_info'] : null);
        return $handler->handle($request);
    }

}
