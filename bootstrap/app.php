<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($e instanceof ValidationException || $e instanceof AuthenticationException) {
                return null;
            }

            $statusDefaults = function (int $status): array {
                return match ($status) {
                    401 => ['title' => 'يجب تسجيل الدخول', 'message' => 'يرجى تسجيل الدخول للمتابعة.'],
                    403 => ['title' => 'غير مصرح لك بالوصول', 'message' => 'نأسف، لا تملك الصلاحيات الكافية لعرض هذه الصفحة.'],
                    404 => ['title' => 'الصفحة غير موجودة', 'message' => 'يبدو أن الرابط غير صحيح أو ربما تم نقل الصفحة.'],
                    419 => ['title' => 'انتهت صلاحية الجلسة', 'message' => 'يرجى تحديث الصفحة وإعادة المحاولة.'],
                    429 => ['title' => 'طلبات كثيرة جداً', 'message' => 'يرجى الانتظار قليلاً قبل إعادة المحاولة مرة أخرى.'],
                    503 => ['title' => 'الخدمة غير متاحة مؤقتاً', 'message' => 'نقوم حالياً ببعض أعمال الصيانة. حاول مرة أخرى لاحقاً.'],
                    default => ['title' => 'حدث خطأ', 'message' => 'نأسف، لم نتمكن من معالجة طلبك في الوقت الحالي.'],
                };
            };

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $defaults = $statusDefaults($status);
                $view = view()->exists("errors.$status") ? "errors.$status" : 'errors.generic';

                return response()->view($view, [
                    'status' => $status,
                    'title' => $defaults['title'],
                    'message' => $e->getMessage() !== '' ? $e->getMessage() : $defaults['message'],
                ], $status);
            }

            $traceLines = config('app.debug')
                ? collect($e->getTrace())->map(function (array $step, int $index) {
                    $location = ($step['file'] ?? 'unknown') . (isset($step['line']) ? ':' . $step['line'] : '');
                    $callable = $step['function'] ?? 'closure';

                    if (isset($step['class'])) {
                        $callable = $step['class'] . ($step['type'] ?? '::') . $callable;
                    }

                    return sprintf('#%d %s %s', $index, $location, $callable);
                })->implode(PHP_EOL)
                : null;

            return response()->view('errors.generic', [
                'status' => 500,
                'title' => 'عطل في الخادم',
                'message' => config('app.debug')
                    ? ($e->getMessage() !== '' ? $e->getMessage() : 'تم رصد استثناء غير معروف.')
                    : 'نأسف، حدث خطأ غير متوقع. يرجى المحاولة لاحقاً.',
                'trace' => $traceLines,
            ], 500);
        });
    })
    ->create();
