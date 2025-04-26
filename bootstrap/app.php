<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\{AuthenticationException, Access\AuthorizationException};
use Symfony\Component\HttpFoundation\Response;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'message' => trans('message.errors.validation.invalid_data'),
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => trans('message.errors.auth.unauthorized'),
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'message' => trans('message.errors.auth.forbidden'),
            ], Response::HTTP_FORBIDDEN);
        });

        // $exceptions->render(function (ModelNotFoundException $e, $request) {
        //     return response()->json([
        //         'message' => 'Không tìm thấy dữ liệu.',
        //     ], Response::HTTP_NOT_FOUND);
        // });

        // $exceptions->render(function (\Throwable $e, $request) {
        //     return response()->json([
        //         'message' => 'Đã xảy ra lỗi, vui lòng thử lại sau.',
        //         'error' => config('app.debug') ? $e->getMessage() : null,
        //     ], Response::HTTP_INTERNAL_SERVER_ERROR);
        // });
    })->create();
