<?php declare(strict_types = 1);

namespace spec\CEmerson\AceAuth\PasswordHashingStrategies;

use Defuse\Crypto\Key;
use PhpSpec\ObjectBehavior;

class PasswordLockPasswordHashingStrategySpec extends ObjectBehavior
{
    use PasswordHashingStrategyTestTrait;

    function let()
    {
        $this->beConstructedWith(Key::createNewRandomKey());
    }
}
