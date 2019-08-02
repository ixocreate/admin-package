<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Admin\Permission;

use DateTime;
use DateTimeInterface;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Permission\Voter\VoterInterface;
use Ixocreate\Admin\Permission\Voter\VoterSubManager;
use Ixocreate\Admin\RoleInterface;
use Ixocreate\Admin\UserInterface;
use Ixocreate\Admin\VoterProviderInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Ixocreate\Admin\Permission\Permission
 */
class PermissionTest extends TestCase
{
    public function setUp()
    {
        $voterSubManager = $this->createMock(VoterSubManager::class);
        $voterSubManager->method('get')->willReturnCallback(function ($requestedName) {
            $mock = $this->createMock(VoterInterface::class);
            switch ($requestedName) {
                case 'own-article':
                    $mock->method('supports')->willReturn(true);
                    $mock->method('vote')->willReturnCallback(function ($user, $subject) {
                        return $subject instanceof DateTimeInterface;
                    });
                    break;
                case 'special-check':
                    $mock->method('supports')->willReturn(true);
                    $mock->method('vote')->willReturnCallback(function ($user, $subject) {
                        return $subject instanceof DateTimeInterface;
                    });
                    break;
                case 'something-else':
                    $mock->method('supports')->willReturn(false);
                    break;

            }

            return $mock;
        });

        Permission::initialize($voterSubManager);
    }

    private function createUser(RoleInterface $role): UserInterface
    {
        return new class($role) implements UserInterface {
            /**
             * @var RoleInterface
             */
            private $role;

            public function __construct(RoleInterface $role)
            {
                $this->role = $role;
            }

            /**
             * @return RoleInterface
             */
            public function getRole(): RoleInterface
            {
                return $this->role;
            }
        };
    }

    private function createAdminRole(): RoleInterface
    {
        return new class() implements RoleInterface, VoterProviderInterface {
            public static function serviceName(): string
            {
                return 'test-role';
            }

            /**
             * @return string
             */
            public function getLabel(): string
            {
                return 'Test Role';
            }

            /**
             * @return array
             */
            public function getPermissions(): array
            {
                return [
                    '*',
                ];
            }

            /**
             * @return string[]
             */
            public function voters(): array
            {
                return [
                    'own-article',
                ];
            }
        };
    }

    private function createUserRole(): RoleInterface
    {
        return new class() implements RoleInterface, VoterProviderInterface {
            public static function serviceName(): string
            {
                return 'test-role';
            }

            /**
             * @return string
             */
            public function getLabel(): string
            {
                return 'Test Role';
            }

            /**
             * @return array
             */
            public function getPermissions(): array
            {
                return [
                    'users.*',
                    'resource.index',
                    'resource.view',
                    'some.thing.to',
                ];
            }

            /**
             * @return string[]
             */
            public function voters(): array
            {
                return [
                    'own-article',
                    'special-check',
                    'something-else',
                ];
            }
        };
    }

    private function createGuestRole(): RoleInterface
    {
        return new class() implements RoleInterface {
            public static function serviceName(): string
            {
                return 'test-role';
            }

            /**
             * @return string
             */
            public function getLabel(): string
            {
                return 'Test Role';
            }

            /**
             * @return array
             */
            public function getPermissions(): array
            {
                return [
                    'login',
                ];
            }
        };
    }

    private function createSomethingElseRole(): RoleInterface
    {
        return new class() implements RoleInterface, VoterProviderInterface {
            public static function serviceName(): string
            {
                return 'test-role';
            }

            /**
             * @return string
             */
            public function getLabel(): string
            {
                return 'Test Role';
            }

            /**
             * @return array
             */
            public function getPermissions(): array
            {
                return [
                    'login',
                ];
            }

            /**
             * @return string[]
             */
            public function voters(): array
            {
                return [
                    'something-else',
                ];
            }
        };
    }

    public function testWithUser()
    {
        $permission = new Permission($this->createUser($this->createAdminRole()));
        $newPermission = $permission->withUser($this->createUser($this->createGuestRole()));

        $this->assertNotSame($permission, $newPermission);

        $this->assertTrue($permission->can('user.index'));
        $this->assertFalse($newPermission->can('user.index'));
    }

    public function testString()
    {
        $permission = new Permission($this->createUser($this->createAdminRole()));

        $this->assertTrue($permission->can('user.index'));
        $this->assertTrue($permission->can('user'));

        $permission = new Permission($this->createUser($this->createUserRole()));
        $this->assertTrue($permission->can('users.sub1.sub2.sub3.sub4'));
        $this->assertTrue($permission->can('users'));
        $this->assertFalse($permission->can('resource.edit'));
        $this->assertFalse($permission->can('resource'));
        $this->assertTrue($permission->can('resource.view'));
        $this->assertTrue($permission->can('resource.index'));
        $this->assertTrue($permission->can('some.thing.to.test'));
    }

    public function testWithOutVoter()
    {
        $permission = new Permission($this->createUser($this->createGuestRole()));

        $this->assertFalse($permission->can(new DateTime()));
    }

    public function testVoter()
    {
        $permission = new Permission($this->createUser($this->createAdminRole()));
        $this->assertTrue($permission->can(new DateTime()));
        $this->assertFalse($permission->can(new stdClass()));

        $permission = new Permission($this->createUser($this->createUserRole()));
        $this->assertTrue($permission->can(new DateTime()));
    }

    public function testNoSupport()
    {
        $permission = new Permission($this->createUser($this->createSomethingElseRole()));

        $this->assertFalse($permission->can(new stdClass()));
    }
}
