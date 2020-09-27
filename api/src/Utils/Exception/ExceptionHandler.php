<?php declare(strict_types = 1);

namespace App\Utils\Exception;

use App\Utils\Logger\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ExceptionHandler
 */
class ExceptionHandler
{
    /** @var Logger $logger */
    private $logger;

    /** @param Logger $logger */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Throwable $exception
     *
     * @return JsonResponse
     */
    public function execute(\Throwable $exception): JsonResponse
    {
        switch (\get_class($exception)) {
            case AccessDeniedException::class:
                $type = 'warning';
                $message = 'error.forbidden';
                $status = JsonResponse::HTTP_FORBIDDEN;
                break;

            case NotFoundHttpException::class:
                $type = 'info';
                $message = 'error.notFound';
                $status = JsonResponse::HTTP_NOT_FOUND;
                break;

            default:
                $type = 'error';
                $message = 'error.unexpectedError';
                $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        $this->logger->log($exception, $type);

        return new JsonResponse($message, $status);
    }
}
