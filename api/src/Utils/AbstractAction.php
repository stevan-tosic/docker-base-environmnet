<?php declare(strict_types = 1);

namespace App\Utils;

use App\Utils\Exception\ExceptionHandler;
use App\Utils\Logger\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AbstractAction
 *
 * In time, actions should replace Controllers
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class AbstractAction
{
    /** @var Logger */
    protected $logger;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var ExceptionHandler */
    protected $exceptionHandler;

    /**
     * AbstractAction constructor.
     *
     * @param Logger                        $logger
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     * @param ExceptionHandler              $exceptionHandler
     */
    public function __construct(
        Logger $logger,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ExceptionHandler $exceptionHandler
    ) {
        $this->logger               = $logger;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage         = $tokenStorage;
        $this->exceptionHandler     = $exceptionHandler;
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * {@inheritdoc}
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        /** @var TokenInterface $token */
        if (!\is_object($user)) {
            return;
        }

        return $user;
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * {@inheritdoc}
     *
     * @return bool
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $subject);
    }

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied subject.
     *
     * {@inheritdoc}
     */
    protected function denyAccessUnlessGranted($attributes, $subject = null, string $message = 'Access Denied.')
    {
        if (!$this->isGranted($attributes, $subject)) {
            $exception = new AccessDeniedException($message);
            $exception->setAttributes($attributes);
            $exception->setSubject($subject);

            throw $exception;
        }
    }

    /**
     * Get content from Body and return it as array
     *
     * @param Request $request
     *
     * @return array|null
     */
    protected function getJsonContent(Request $request): ?array
    {
        if ($request->getContent() && $request->getContentType() === 'json') {
            return \json_decode($request->getContent(), true);
        }

        return null;
    }
}
