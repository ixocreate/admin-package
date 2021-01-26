<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Admin;

use Ixocreate\Admin\AdminBootstrapItem;
use Ixocreate\Admin\Package;
use Ixocreate\Admin\Permission\Voter\VoterSubManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    /**
     * @covers \Ixocreate\Admin\Package
     */
    public function testPackage()
    {
        $serviceManager = $this->getMockBuilder(ServiceManagerInterface::class)->getMock();
        $serviceManager->method('get')->willReturn($this->createMock(VoterSubManager::class));

        $package = new Package();
        $package->boot($serviceManager);

        $this->assertSame([AdminBootstrapItem::class], $package->getBootstrapItems());
        $this->assertDirectoryExists($package->getBootstrapDirectory());
        $this->assertSame([
            \Ixocreate\Media\Package::class,
            \Ixocreate\Cms\Package::class,
            \Ixocreate\Intl\Package::class,
        ], $package->getDependencies());
    }
}
