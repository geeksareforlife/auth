<?php declare(strict_types = 1);

namespace CEmerson\Auth\Session;

final class PHPSessionGateway implements SessionGateway
{
    /** @var string */
    private $cookieDomain;

    public function __construct(string $cookieDomain)
    {
        $this->cookieDomain = $cookieDomain;
    }

    public function start()
    {
        ini_set('session.use_cookies', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.entropy_length', '32');
        ini_set('session.entropy_file', '/dev/urandom');
        ini_set('session.hash_function', 'sha256');
        ini_set('session.hash_bits_per_character', '5');

        session_set_cookie_params(
            0,
            '/',
            $this->cookieDomain,
            true,
            true
        );

        session_start();
    }

    public function read(string $name)
    {
        return $_SESSION[$name];
    }

    public function write(string $name, $data)
    {
        $_SESSION[$name] = $data;
    }

    public function exists(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function regenerate()
    {
        session_regenerate_id(true);
    }

    public function delete(string $name)
    {
        unset($_SESSION[$name]);
    }
}
