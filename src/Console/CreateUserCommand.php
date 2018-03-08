<?php
namespace KiwiSuite\Admin\Console;

use KiwiSuite\Admin\Message\CreateUserMessage;
use KiwiSuite\ApplicationConsole\Command\CommandInterface;
use KiwiSuite\CommandBus\CommandBus;
use KiwiSuite\CommandBus\Message\MessageSubManager;
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
     * @var MessageSubManager
     */
    private $messageSubManager;

    /**
     * CreateUserCommand constructor.
     * @param CommandBus $commandBus
     * @param MessageSubManager $messageSubManager
     */
    public function __construct(CommandBus $commandBus, MessageSubManager $messageSubManager)
    {
        $this->commandBus = $commandBus;
        parent::__construct(self::getCommandName());
        $this->messageSubManager = $messageSubManager;
    }

    public function configure()
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address.')
            ->addArgument('role', InputArgument::REQUIRED, 'Role')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $password = substr(base64_encode(sha1(uniqid())), 0, 8);

        /** @var CreateUserMessage $message */
        $message = $this->messageSubManager->build(CreateUserMessage::class);
        $message = $message->inject([
            'email' => $input->getArgument("email"),
            'role' => $input->getArgument("role"),
            'password' => $password,
            'passwordRepeat' => $password,
        ]);
        $result = $message->validate();
        if (!$result->isSuccessful()) {

        }

        $this->commandBus->handle($message);

        $output->writeln("Password: ". $password);
    }

    public static function getCommandName()
    {
        return "admin:create-user";
    }
}
