<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $e, $request) {
            return renderCustomErrorPage($request, 403);
        });

        $exceptions->render(function (TokenMismatchException $e, $request) {
            return renderCustomErrorPage($request, 419);
        });

        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            return renderCustomErrorPage($request, $e->getStatusCode());
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if (! app()->environment('production')) {
                return null;
            }

            return renderCustomErrorPage($request, 500);
        });
    })
    ->create();

function renderCustomErrorPage($request, int $status)
{
    $supportedErrors = [
        401,
        403,
        404,
        405,
        419,
        429,
        500,
        502,
        503,
        504,
    ];

    $status = in_array($status, $supportedErrors, true) ? $status : 500;

    if ($request->is('admin') || $request->is('admin/*')) {
        $view = "errors.admin.{$status}";

        if (view()->exists($view)) {
            return response()->view($view, [], $status);
        }

        return response()->view('errors.admin.500', [], 500);
    }

    $view = "errors.{$status}";

    if (view()->exists($view)) {
        return response()->view($view, [], $status);
    }

    return response()->view('errors.500', [], 500);
}