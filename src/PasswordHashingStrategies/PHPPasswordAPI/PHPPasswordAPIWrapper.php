<?php  declare(strict_types = 1);

namespace CEmerson\AceAuth\PasswordHashingStrategies\PHPPasswordAPI;

interface PHPPasswordAPIWrapper
{
    public function hash(string $password, int $algo = PASSWORD_DEFAULT, array $options = []) : string;

    public function verify(string $password, string $hash) : bool;
}
