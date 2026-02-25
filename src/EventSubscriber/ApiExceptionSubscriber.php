<?php

namespace App\EventSubscriber;

use App\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // ATTENTION AU NOM DU PREFIXE 
        if (str_starts_with($path, '/api/v1/chantiers')) {
            return;
        }

        $e = $event->getThrowable();

        // Valeurs par défaut (500)
        $status = 500;
        $code = 'INTERNAL_ERROR';
        $message = 'Une erreur interne est survenue';
        $details = null;

        // 1) Exceptions métier à préciser
        if ($e instanceof ApiException) {
            $status = $e->getStatus();
            $code = $e->getCode();
            $message = $e->getMessage();
            $details = $e->getDetails();
        }
        // 2) Exceptions HTTP Symfony (404, 403, etc.)
        elseif ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();

            if ($status === 404) {
                // ✅ Message propre : on ne renvoie PAS le message technique Symfony
                if (str_starts_with($path, '/api/v1/chantiers')) {
                    $code = 'CHANTIER_NOT_FOUND';
                    $message = 'Chantier non trouvé';
                } else {
                    $code = 'NOT_FOUND';
                    $message = 'Ressource introuvable';
                }
            } else {
                $code = match ($status) {
                    401 => 'UNAUTHORIZED',
                    403 => 'FORBIDDEN',
                    405 => 'METHOD_NOT_ALLOWED',
                    default => 'HTTP_ERROR',
                };

                // Pour 401/403/405/etc, on garde le message si présent, sinon générique
                $message = $e->getMessage() ?: $message;
            }
        }

        $payload = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'meta' => [
                'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
                'path' => $path,
            ],
        ];

        $event->setResponse(new JsonResponse($payload, $status));
    }
}