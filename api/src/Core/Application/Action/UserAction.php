<?php declare(strict_types = 1);

namespace App\Core\Application\Action;

use App\Core\Application\Security\Voter\UserVoter;
use App\Core\Application\Service\UserService;
use App\Core\Domain\Entity\User\User;
use App\Utils\AbstractAction;
use App\Utils\Logger\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserAction
 */
class UserAction extends AbstractAction
{
    /** @var UserService */
    protected $userService;

    /**
     * UserAction constructor.
     *
     * @param Logger                        $logger
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     * @param UserService                   $userService
     */
    public function __construct(
        Logger $logger,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        UserService $userService
    ) {
        parent::__construct($logger, $authorizationChecker, $tokenStorage);
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            $data = \json_decode($request->getContent(), true);
            $this->denyAccessUnlessGranted(UserVoter::CREATE, $user);

            $result = $this->userService->execute($user, $data);

            if (!empty($result['errors'])) {
                $this->logger->log(\json_encode($result['errors'])." in ".self::class, 'info');

                return new JsonResponse($result["errors"], JsonResponse::HTTP_BAD_REQUEST);
            }

            return new JsonResponse(["data" => $result["data"]], JsonResponse::HTTP_CREATED);
        } catch (AccessDeniedException $exception) {
            $this->logger->log($exception, 'warning');

            return new JsonResponse('error.forbidden', JsonResponse::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            $this->logger->log($exception, 'error');

            return new JsonResponse('error.unexpectedError', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
