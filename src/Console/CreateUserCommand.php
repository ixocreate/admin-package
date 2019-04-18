<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Console;

use Ixocreate\CommandBus\CommandBus;
use Ixocreate\Application\Console\CommandInterface;;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'set password', false)
        ;

        $this->setDescription('Creates a new admin user with a random generated password.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!empty($input->getOption('password'))) {
            $password = $input->getOption('password');
        } elseif ($input->getOption('password') === null) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('password: ');
            $question->setHidden(true);

            $password = $helper->ask($input, $output, $question);

            $question = new Question('repeat password: ');
            $question->setHidden(true);

            $repeatPassword = $helper->ask($input, $output, $question);
            if ($password !== $repeatPassword) {
                $output->writeln('<error>passwords does not match</error>');
                return;
            }
        } else {
            $password = \mb_substr(\base64_encode(\sha1(\uniqid())), 0, 10);
            $output->writeln("Password: " . $password);
        }

        $data = [
            'email' => $input->getArgument("email"),
            'role' => $input->getArgument("role"),
            'password' => $password,
            'passwordRepeat' => $password,
            'status' => 'active',
        ];

        $result = $this->commandBus->command(\Ixocreate\Package\Admin\Command\User\CreateUserCommand::class, $data);

        if (!$result->isSuccessful()) {
            //
        }
    }

    public static function getCommandName()
    {
        return "admin:create-user";
    }
}
