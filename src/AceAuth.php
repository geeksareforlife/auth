<?php declare(strict_types = 1);

namespace CEmerson\AceAuth;

use CEmerson\AceAuth\Exceptions\NoUserLoggedIn;
use CEmerson\AceAuth\Exceptions\UserNotFound;
use CEmerson\AceAuth\Session\Session;
use CEmerson\AceAuth\Users\User;
use CEmerson\AceAuth\Users\UserGateway;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

final class AceAuth implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var UserGateway */
    private $userGateway;

    /** @var Session */
    private $session;

    public function __construct(UserGateway $userGateway, Session $session)
    {
        $this->userGateway = $userGateway;
        $this->session = $session;

        $this->setLogger(new NullLogger());
    }

    public function login(string $username, string $password, bool $rememberMe = false): bool
    {
        try {
            $user = $this->userGateway->findUserByUsername($username);
        } catch (UserNotFound $e) {
            return false;
        }

        return $this->attemptUserAuthentication($user, $password);
    }

    private function attemptUserAuthentication(User $user, string $password): bool
    {
        if ($user->verifyPassword($password)) {
            $this->session->onSuccessfulAuthentication($user);

            return true;
        }

        return false;
    }

    public function logout()
    {
        $this->session->deleteAceAuthSessionInfo();
    }

    public function isLoggedIn(): bool
    {
        return $this->session->userIsLoggedIn();
    }

    public function getCurrentUser(): User
    {
        if (!$this->isLoggedIn()) {
            throw new NoUserLoggedIn();
        }

        return $this->userGateway->findUserByUsername(
            $this->session->getLoggedInUsername()
        );
    }

    public function hasAuthenticatedThisSession(): bool
    {
        return $this->session->userHasAuthenticatedThisSession();
    }
}
