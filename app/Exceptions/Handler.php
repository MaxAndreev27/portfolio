<?php

namespace App\Exceptions;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function render($request, Throwable $e)
    {
        if ($request->header('X-Inertia')) {
            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            $messages = [
                400 => 'Невірний запит',
                401 => 'Необхідна авторизація',
                403 => 'Доступ заборонено',
                404 => 'Сторінку не знайдено',
                419 => 'Термін дії сторінки закінчився',
                422 => 'Помилка валідації',
                429 => 'Забагато запитів',
                500 => 'Внутрішня помилка сервера',
                503 => 'Сервіс тимчасово недоступний',
                -1   => 'Щось пішло не так', // Default message
            ];

            $message = $messages[$status] ?? $messages[500];

            return Inertia::render('Errors/Error', [
                'status' => $status,
                'message' => $message,
                'exception' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ])->toResponse($request)->setStatusCode($status);
        }

        return parent::render($request, $e);
    }
}
