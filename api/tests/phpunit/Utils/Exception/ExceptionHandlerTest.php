<?php declare(strict_types = 1);

namespace App\Utils\Doctrine\DQL\DateTime;

use App\Utils\Exception\ExceptionHandler;
use App\Utils\Logger\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ExceptionHandlerTest
 */
class ExceptionHandlerTest extends TestCase
{
    /** @var ExceptionHandler */
    private $instance;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * ExceptionHandlerTest constructor
     */
    public function setUp(): void
    {
        $this->logger = $this->prophesize(Logger::class);
        $this->instance = new ExceptionHandler($this->logger->reveal());
    }

    /** @dataProvider exceptionDataProvider
     *
     * @param $exception
     * @param $message
     * @param $expectedStatusCode
     */
    public function testExecute($exception, $message, $expectedStatusCode): void
    {
        $result = $this->instance->execute($exception);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($message, $result->getContent());
        $this->assertEquals($expectedStatusCode, $result->getStatusCode());
    }

    /**
     * @return array[]
     */
    public function exceptionDataProvider()
    {
        return [
            [
                new \Exception('Exception'),
                '"error.unexpectedError"',
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            ],
            [
                new AccessDeniedException('Access Denied'),
                '"error.forbidden"',
                JsonResponse::HTTP_FORBIDDEN,
            ],
            [
                new NotFoundHttpException('Not Found'),
                '"error.notFound"',
                JsonResponse::HTTP_NOT_FOUND,
            ],
        ];
    }
}
