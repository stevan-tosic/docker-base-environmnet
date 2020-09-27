<?php declare(strict_types=1);

namespace App\Core\Application\Action;

use App\Core\Application\Security\Voter\UserVoter;
use App\Core\Application\Service\UserService;
use App\Core\Domain\Entity\User\User;
use App\Utils\AbstractAction;
use App\Utils\Exception\ExceptionHandler;
use App\Utils\Logger\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @param ExceptionHandler              $exceptionHandler
     * @param UserService                   $userService
     */
    public function __construct(
        Logger $logger,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        ExceptionHandler $exceptionHandler,
        UserService $userService
    ) {
        parent::__construct($logger, $authorizationChecker, $tokenStorage, $exceptionHandler);
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

            return new JsonResponse(["data" => $result["data"]], JsonResponse::HTTP_CREATED);
        } catch (\Throwable $exception) {
            return $this->exceptionHandler->execute($exception);
        }
    }
}
