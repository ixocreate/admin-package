<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Console;

use KiwiSuite\CommandBus\CommandBus;
use KiwiSuite\Contract\Command\CommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserCommand extends Command implements CommandInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * CreateUserCommand constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct(self::getCommandName());
    }

    public function configure()
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address')
            ->addArgument('role', InputArgument::REQUIRED, 'Role')
        ;

        $this->setDescription('Creates a new admin user with a random generated password.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $password = \mb_substr(\base64_encode(\sha1(\uniqid())), 0, 10);

        $data = [
            'email' => $input->getArgument("email"),
            'role' => $input->getArgument("role"),
            'password' => $password,
            'passwordRepeat' => $password,
            'status' => 'active',
        ];

        $result = $this->commandBus->command(\KiwiSuite\Admin\Command\User\CreateUserCommand::class, $data);

        if (!$result->isSuccessful()) {
        }

        $output->writeln("Password: " . $password);
    }

    public static function getCommandName()
    {
        return "admin:create-user";
    }
}
