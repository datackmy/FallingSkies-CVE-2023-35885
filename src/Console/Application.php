<?php
namespace App\Console;
use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Command\ListCommand;
class Application extends BaseApplication
{
    const APPLICATION_NAME = "\x43\x6c\x6f\x75\x64\x50\141\156\x65\154\40\x43\x4c\x49";
    const DEFAULT_COMMAND_NAME = "\x61\x70\x70\x3a\x6c\151\x73\164";
    const SYSTEM_USER_ROOT = "\162\x6f\x6f\x74";
    const SYSTEM_USER_CLP = "\143\x6c\160";
    private KernelInterface $kernel;
    private bool $commandsRegistered = false;
    private array $registrationErrors = [];
    public function __construct(KernelInterface $kernel)
    {
        goto e414f;
        c74e4:
        parent::__construct($this->kernel);
        goto E71bb;
        E71bb:
        $this->setDefaultCommand(self::DEFAULT_COMMAND_NAME);
        goto Fdfd9;
        e414f:
        $this->kernel = $kernel;
        goto c74e4;
        Fdfd9:
    }
    public function add(Command $command): ?Command
    {
        $this->registerCommands();
        return parent::add($command);
    }
    protected function registerCommands(): void
    {
        goto dab05;
        f1650:
        return;
        goto E4d2e;
        f5bba:
        $this->initCommandLoader();
        goto dda05;
        d6dd6:
        $this->commandsRegistered = true;
        goto c9932;
        E4d2e:
        A0543:
        goto d6dd6;
        dab05:
        if (!$this->commandsRegistered) {
            goto A0543;
        }
        goto f1650;
        c9932:
        $this->kernel->boot();
        goto f5bba;
        dda05:
    }
    private function renderRegistrationErrors(InputInterface $input, OutputInterface $output): void
    {
        goto c109f;
        a28bb:
        b810d:
        goto A5eb0;
        e8012:
        $output = $output->getErrorOutput();
        goto a28bb;
        Fa1bb:
        a9078:
        goto F6d34;
        A5eb0:
        (new SymfonyStyle($input, $output))->warning("\x53\x6f\x6d\x65\40\143\x6f\x6d\155\x61\x6e\x64\x73\x20\x63\157\x75\154\144\40\x6e\157\x74\x20\142\x65\40\x72\x65\x67\151\x73\x74\x65\x72\x65\144\72");
        goto a9af2;
        c109f:
        if (!$output instanceof ConsoleOutputInterface) {
            goto b810d;
        }
        goto e8012;
        a9af2:
        foreach ($this->registrationErrors as $error) {
            $this->doRenderThrowable($error, $output);
            A9cce:
        }
        goto Fa1bb;
        F6d34:
    }
    private function initCommandLoader(): void
    {
        $commandLoader = $this->getCommandLoader();
        $this->setCommandLoader($commandLoader);
    }
    public function getCommandLoader(): FactoryCommandLoader
    {
        goto De274;
        Ab8b3:
        $clpCommands = $this->getCommands("\143\x6c\x70");
        goto Dc195;
        f140e:
        $container = $this->getContainer();
        goto A584e;
        e0e5c:
        $systemUserName = $_SERVER["\123\x55\x44\117\x5f\x55\x53\x45\122"] ?? '';
        goto C11c5;
        Aa3f1:
        $commandLoader = new FactoryCommandLoader($commands);
        goto E3a1c;
        Db704:
        Cc45a:
        goto Aa3f1;
        De274:
        if ("\x64\145\166" == $_ENV["\101\x50\x50\x5f\105\x4e\x56"]) {
            goto Caa26;
        }
        goto e0e5c;
        Dc195:
        $commands = array_merge($rootCommands, $clpCommands);
        goto c6579;
        C11c5:
        $commands = $this->getCommands($systemUserName);
        goto E90a0;
        A584e:
        $rootCommands = $this->getCommands("\162\157\x6f\x74");
        goto Ab8b3;
        E09c7:
        $commands["\x76\141\162\x6e\151\163\x68\55\x63\141\143\x68\145\72\160\165\x72\147\145"] = function () use ($container) {
            return $container->get("\101\x70\160\134\x43\157\155\155\141\156\x64\134\x56\x61\x72\x6e\x69\x73\150\x43\x61\143\x68\x65\x50\165\x72\147\x65\x43\x6f\155\x6d\x61\156\144"); };
        goto Db704;
        E3a1c:
        return $commandLoader;
        goto bb09c;
        E90a0:
        goto Cc45a;
        goto b7182;
        c6579:
        $commands["\x74\x65\x73\164\72\x74\x65\163\x74"] = function () use ($container) {
            return $container->get("\101\x70\x70\x5c\x43\157\155\x6d\141\156\144\x5c\124\x65\163\x74\103\x6f\155\x6d\141\156\x64"); };
        goto E09c7;
        b7182:
        Caa26:
        goto f140e;
        bb09c:
    }
    public function getCommands($systemUserName): array
    {
        goto e8568;
        e8568:
        $container = $this->getContainer();
        goto d0511;
        Dc719:
        fd9f3:
        goto Ec631;
        Ec631:
        return $commands;
        goto c47dd;
        d0511:
        switch ($systemUserName) {
            case self::SYSTEM_USER_ROOT:
                $commands = ["\143\154\157\165\144\x70\141\156\x65\154\x3a\145\156\141\x62\x6c\145\x3a\142\141\x73\151\143\x2d\141\165\164\x68" => function () use ($container) {
                    return $container->get("\101\160\160\x5c\103\x6f\x6d\x6d\141\156\144\134\x43\154\x6f\x75\144\120\141\156\x65\154\x45\156\x61\x62\154\x65\x42\141\163\151\x63\x41\x75\164\150\103\157\x6d\155\x61\x6e\x64"); }, "\x63\x6c\x6f\165\144\160\141\x6e\145\x6c\x3a\x64\x69\x73\x61\x62\x6c\145\72\x62\141\x73\x69\x63\55\x61\x75\x74\x68" => function () use ($container) {
                        return $container->get("\x41\160\160\134\103\157\155\x6d\141\x6e\144\x5c\x43\154\x6f\165\144\120\x61\x6e\x65\x6c\x44\x69\x73\x61\142\154\x65\102\x61\x73\151\143\x41\x75\164\x68\x43\x6f\155\155\141\156\144"); }, "\x63\154\157\x75\144\x70\x61\x6e\145\x6c\72\163\x65\x74\72\x72\145\x6c\145\141\163\145\x2d\x63\x68\141\156\156\x65\154" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\x43\157\x6d\x6d\x61\x6e\144\x5c\103\154\157\165\x64\x50\x61\x6e\x65\x6c\x53\x65\164\x52\145\154\145\x61\x73\145\x43\x68\141\156\156\x65\x6c\103\x6f\x6d\x6d\x61\156\144"); }, "\x63\154\157\x75\x64\x66\x6c\141\x72\x65\x3a\165\160\144\141\x74\x65\72\x69\x70\x73" => function () use ($container) {
                        return $container->get("\x41\160\x70\x5c\x43\157\155\x6d\x61\156\144\x5c\x43\x6c\x6f\x75\144\146\x6c\141\x72\145\x55\160\x64\141\164\145\x49\x70\163\103\x6f\155\155\x61\x6e\x64"); }, "\x64\x62\x3a\x73\150\157\167\x3a\155\141\x73\164\145\x72\x2d\x63\162\x65\144\x65\x6e\x74\x69\x61\x6c\163" => function () use ($container) {
                        return $container->get("\x41\x70\160\x5c\x43\157\155\x6d\x61\x6e\144\134\104\x61\164\141\142\141\163\145\123\150\x6f\x77\115\x61\x73\164\145\x72\103\162\x65\144\145\156\x74\151\x61\x6c\x73\103\x6f\155\155\x61\156\144"); }, "\x64\142\x3a\141\144\x64" => function () use ($container) {
                        return $container->get("\x41\160\160\134\103\x6f\x6d\x6d\141\156\x64\134\x44\x61\x74\x61\x62\x61\163\145\101\x64\144\103\x6f\x6d\x6d\x61\156\144"); }, "\144\142\x3a\145\170\160\x6f\x72\164" => function () use ($container) {
                        return $container->get("\101\x70\160\134\103\157\x6d\x6d\x61\x6e\144\x5c\104\x61\x74\x61\142\141\163\145\105\170\x70\157\162\x74\x43\x6f\155\155\141\x6e\x64"); }, "\144\x62\72\x69\x6d\x70\x6f\162\x74" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\103\x6f\x6d\x6d\141\x6e\x64\x5c\x44\141\164\141\x62\141\x73\x65\x49\x6d\160\157\162\164\x43\157\155\155\141\x6e\144"); }, "\x64\x62\72\x64\145\x6c\145\x74\145" => function () use ($container) {
                        return $container->get("\x41\x70\x70\x5c\103\157\155\x6d\x61\x6e\144\134\x44\x61\164\x61\x62\x61\x73\x65\x44\145\154\145\164\145\x43\x6f\x6d\x6d\141\156\x64"); }, "\154\145\x74\x73\55\x65\156\x63\x72\x79\160\164\72\151\156\163\x74\141\x6c\x6c\x3a\143\x65\x72\164\151\146\x69\x63\141\x74\145" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\x43\157\x6d\x6d\x61\x6e\144\x5c\x4c\145\164\x73\105\156\143\162\x79\x70\164\x49\156\163\x74\x61\x6c\154\103\x65\162\x74\x69\146\151\143\141\164\x65\103\x6f\x6d\155\141\x6e\144"); }, "\x73\x69\164\145\x3a\x61\144\x64\72\x6e\157\144\x65\152\x73" => function () use ($container) {
                        return $container->get("\101\x70\160\x5c\x43\x6f\x6d\155\141\x6e\144\134\x53\151\164\x65\x41\x64\x64\x4e\x6f\144\x65\x6a\x73\x43\x6f\x6d\155\x61\x6e\144"); }, "\163\151\x74\x65\x3a\x61\x64\144\x3a\x73\x74\x61\x74\151\143" => function () use ($container) {
                        return $container->get("\101\x70\160\x5c\103\157\155\155\x61\156\144\134\123\x69\x74\x65\x41\144\x64\x53\x74\x61\x74\151\x63\103\x6f\x6d\155\141\x6e\144"); }, "\x73\x69\x74\x65\72\x61\x64\x64\72\x70\150\160" => function () use ($container) {
                        return $container->get("\x41\x70\160\134\x43\x6f\x6d\155\x61\156\x64\134\123\151\164\x65\101\x64\x64\120\150\x70\x43\157\x6d\155\141\156\144"); }, "\x73\x69\x74\145\x3a\141\x64\144\72\160\x79\164\x68\157\x6e" => function () use ($container) {
                        return $container->get("\101\x70\160\134\x43\x6f\x6d\x6d\141\x6e\x64\x5c\x53\x69\164\x65\101\144\x64\x50\171\164\150\157\156\103\157\x6d\155\x61\x6e\x64"); }, "\x73\151\164\x65\72\141\x64\x64\72\162\145\x76\145\x72\163\145\x2d\x70\x72\157\x78\x79" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\103\x6f\155\x6d\x61\x6e\144\134\123\151\164\145\x41\144\144\x52\145\166\x65\162\163\x65\x50\162\157\x78\x79\103\157\x6d\x6d\141\x6e\144"); }, "\x73\x69\164\x65\x3a\144\145\x6c\145\x74\145" => function () use ($container) {
                        return $container->get("\x41\x70\160\x5c\x43\157\155\x6d\x61\156\144\x5c\x53\x69\x74\145\104\x65\x6c\145\164\145\x43\157\x6d\155\x61\x6e\x64"); }, "\163\x79\163\164\x65\x6d\72\x70\x65\x72\x6d\x69\163\163\151\x6f\156\163\x3a\x72\145\163\145\x74" => function () use ($container) {
                        return $container->get("\101\160\160\134\x43\x6f\155\155\141\x6e\144\x5c\123\171\x73\x74\145\x6d\120\145\x72\155\x69\163\163\151\x6f\x6e\163\122\145\163\145\x74\103\x6f\x6d\155\x61\156\144"); }, "\x75\x73\x65\x72\x3a\x72\145\x73\x65\x74\72\160\141\x73\163\x77\x6f\162\x64" => function () use ($container) {
                        return $container->get("\101\160\x70\134\x43\157\155\155\x61\x6e\x64\134\x55\x73\x65\x72\122\145\163\x65\164\x50\141\163\163\167\x6f\x72\x64\103\157\x6d\x6d\x61\x6e\144"); }, "\165\x73\145\x72\x3a\144\151\x73\x61\142\x6c\x65\72\155\146\141" => function () use ($container) {
                        return $container->get("\x41\x70\x70\x5c\x43\157\x6d\155\141\x6e\x64\134\x55\163\145\162\x44\151\x73\141\142\154\145\115\146\x61\103\157\155\x6d\x61\x6e\x64"); }, "\166\150\157\163\x74\55\x74\145\155\x70\154\141\x74\x65\163\72\151\x6d\160\x6f\162\x74" => function () use ($container) {
                        return $container->get("\x41\160\160\134\103\157\155\155\x61\156\x64\134\126\150\157\163\164\x54\145\155\160\x6c\141\164\145\163\111\155\160\157\162\164\x43\x6f\155\155\141\156\144"); }, "\x76\x68\157\x73\x74\55\164\145\155\x70\x6c\x61\x74\145\163\72\x6c\151\x73\164" => function () use ($container) {
                        return $container->get("\x41\160\x70\x5c\x43\157\x6d\155\141\156\x64\x5c\126\x68\157\163\x74\124\145\155\160\154\141\x74\x65\163\x4c\151\163\164\x43\x6f\155\x6d\x61\156\144"); }, "\x76\150\157\163\164\55\x74\x65\x6d\x70\x6c\141\x74\x65\72\141\x64\x64" => function () use ($container) {
                        return $container->get("\101\x70\x70\x5c\103\157\155\x6d\x61\156\144\x5c\126\x68\x6f\163\x74\124\145\x6d\x70\154\141\x74\x65\x41\144\x64\x43\x6f\155\x6d\x61\156\144"); }, "\166\150\157\163\164\55\x74\145\x6d\x70\x6c\x61\x74\x65\72\x64\145\154\145\164\x65" => function () use ($container) {
                        return $container->get("\101\x70\160\134\103\157\x6d\x6d\x61\156\x64\134\126\150\x6f\163\164\124\x65\x6d\160\x6c\141\164\145\x44\x65\x6c\145\164\x65\x43\157\x6d\x6d\x61\156\x64"); }, "\x76\150\157\163\164\x2d\164\x65\x6d\160\154\x61\164\145\72\166\151\145\167" => function () use ($container) {
                        return $container->get("\x41\x70\160\x5c\103\157\x6d\x6d\141\156\144\x5c\126\x68\157\163\x74\124\145\x6d\x70\x6c\x61\164\x65\126\151\145\167\x43\x6f\155\155\141\x6e\x64"); }];
                goto fd9f3;
            case self::SYSTEM_USER_CLP:
                $commands = ["\141\156\x6e\157\x75\x6e\x63\x65\155\145\156\164\72\x63\150\145\143\x6b" => function () use ($container) {
                    return $container->get("\x41\x70\x70\134\x43\x6f\x6d\155\141\x6e\144\134\x41\x6e\x6e\157\x75\x6e\143\145\155\x65\x6e\x74\x43\150\145\143\x6b\103\157\x6d\x6d\x61\156\x64"); }, "\x61\160\x70\72\x67\145\x74\72\x63\157\x6e\146\151\x67\x2d\x76\x61\154\x75\x65" => function () use ($container) {
                        return $container->get("\x41\x70\x70\134\103\157\155\x6d\141\x6e\144\134\x41\160\160\107\x65\x74\x43\157\x6e\x66\151\x67\126\141\x6c\x75\x65\x43\157\x6d\x6d\141\x6e\x64"); }, "\x61\x70\x70\x3a\x73\145\164\x3a\143\x6f\x6e\x66\x69\147\55\166\x61\x6c\165\145" => function () use ($container) {
                        return $container->get("\x41\x70\x70\134\x43\157\x6d\155\x61\156\144\134\101\160\x70\x53\x65\x74\103\x6f\x6e\x66\x69\x67\126\141\x6c\x75\145\103\157\x6d\155\x61\x6e\144"); }, "\143\x6c\x6f\165\144\146\x6c\x61\162\145\72\165\160\x64\141\164\145\72\151\160\x73" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\x43\157\x6d\x6d\141\x6e\144\x5c\103\154\x6f\165\144\x66\x6c\141\162\145\x55\160\144\141\164\145\x49\160\x73\103\157\155\155\141\156\144"); }, "\143\x6c\157\x75\144\160\x61\x6e\x65\x6c\x3a\x64\145\154\145\164\145\72\x73\x69\164\145\x73" => function () use ($container) {
                        return $container->get("\101\160\x70\134\x43\x6f\x6d\155\141\x6e\x64\134\x43\x6c\x6f\x75\144\x50\141\x6e\x65\x6c\x44\145\x6c\145\164\x65\123\x69\164\x65\163\103\x6f\155\x6d\x61\156\144"); }, "\155\x6f\156\x69\x74\157\162\x69\x6e\147\72\x64\141\164\141\72\x63\x6c\145\x61\x6e" => function () use ($container) {
                        return $container->get("\x41\x70\x70\x5c\103\157\x6d\x6d\141\156\144\134\115\157\156\x69\164\x6f\162\x69\x6e\147\104\141\164\x61\x43\154\x65\x61\156\x43\x6f\155\x6d\x61\x6e\144"); }, "\144\x62\72\142\x61\143\153\x75\160" => function () use ($container) {
                        return $container->get("\x41\160\x70\134\x43\x6f\155\155\x61\x6e\144\134\x44\x61\164\x61\x62\141\x73\x65\x42\x61\143\153\165\x70\x43\x6f\x6d\x6d\141\x6e\144"); }, "\141\x77\163\72\151\155\x61\x67\x65\x3a\143\x72\145\141\x74\145" => function () use ($container) {
                        return $container->get("\101\160\160\134\x43\x6f\155\x6d\141\x6e\144\134\x41\x77\x73\111\x6d\x61\147\x65\x43\x72\145\141\164\x65\x43\157\155\x6d\141\x6e\x64"); }, "\x64\x6f\x3a\163\x6e\141\160\x73\150\157\x74\72\143\x72\x65\x61\164\x65" => function () use ($container) {
                        return $container->get("\101\160\x70\134\x43\x6f\x6d\x6d\x61\x6e\144\134\104\x6f\123\x6e\141\x70\163\x68\157\164\x43\x72\145\x61\x74\x65\x43\x6f\x6d\155\141\156\x64"); }, "\x67\143\x65\72\163\x6e\x61\x70\x73\x68\x6f\x74\x3a\x63\x72\145\141\164\x65" => function () use ($container) {
                        return $container->get("\101\x70\160\x5c\103\157\x6d\155\141\156\144\134\107\x63\145\123\156\x61\160\x73\150\157\x74\103\162\x65\141\x74\145\x43\157\x6d\155\141\x6e\144"); }, "\166\x75\x6c\x74\x72\72\x73\x6e\141\160\x73\150\x6f\164\x3a\x63\162\145\141\164\145" => function () use ($container) {
                        return $container->get("\101\x70\160\134\103\x6f\155\155\x61\x6e\x64\134\x56\165\154\164\162\x53\156\141\160\163\150\157\x74\103\x72\x65\x61\164\x65\x43\x6f\155\x6d\141\x6e\x64"); }, "\x68\145\164\172\156\145\162\x3a\163\156\x61\160\163\150\157\x74\72\x63\x72\145\141\x74\145" => function () use ($container) {
                        return $container->get("\101\160\160\134\103\x6f\155\155\141\156\x64\134\x48\x65\164\x7a\156\x65\x72\x53\x6e\141\160\163\x68\x6f\164\103\162\x65\141\x74\x65\103\x6f\x6d\155\141\156\x64"); }, "\x6c\x65\164\163\55\145\156\143\x72\171\x70\x74\72\x72\x65\x6e\145\x77\72\143\145\x72\x74\x69\146\151\143\x61\x74\x65\163" => function () use ($container) {
                        return $container->get("\x41\160\x70\x5c\103\157\155\155\141\156\144\x5c\x4c\x65\x74\x73\x45\156\x63\x72\171\x70\x74\122\x65\156\x65\167\103\x65\162\x74\x69\146\x69\x63\x61\164\x65\163\103\157\155\x6d\x61\156\144"); }, "\154\145\x74\x73\x2d\x65\156\143\162\x79\x70\164\72\x72\x65\156\x65\167\x3a\x63\165\x73\164\157\x6d\x2d\144\x6f\155\x61\151\156\x3a\143\x65\x72\164\151\x66\x69\x63\x61\164\145" => function () use ($container) {
                        return $container->get("\101\160\160\134\103\157\x6d\155\x61\156\144\134\114\x65\x74\x73\105\156\143\x72\171\160\164\122\145\156\x65\167\x43\x75\x73\x74\x6f\155\x44\x6f\155\x61\151\156\x43\145\x72\164\x69\146\151\x63\141\x74\145\103\157\x6d\x6d\x61\156\x64"); }, "\x73\151\x74\145\72\x64\145\x6c\145\x74\145" => function () use ($container) {
                        return $container->get("\101\160\x70\x5c\x43\x6f\155\x6d\141\156\144\x5c\x53\151\x74\145\x44\145\154\x65\x74\x65\103\x6f\155\x6d\x61\156\x64"); }, "\166\x68\157\x73\164\55\164\x65\155\x70\x6c\141\x74\x65\163\72\x69\155\160\157\x72\164" => function () use ($container) {
                        return $container->get("\x41\160\x70\134\x43\x6f\155\x6d\x61\156\x64\x5c\126\150\x6f\x73\x74\124\x65\155\x70\x6c\141\x74\145\x73\x49\155\x70\x6f\x72\164\103\157\155\155\x61\156\x64"); }, "\x76\x68\x6f\163\164\55\x74\145\155\160\x6c\x61\x74\x65\163\x3a\x6c\151\163\164" => function () use ($container) {
                        return $container->get("\101\x70\x70\x5c\x43\157\x6d\x6d\141\x6e\x64\134\x56\150\157\x73\x74\124\x65\155\160\154\x61\164\x65\163\114\151\163\x74\103\x6f\155\155\x61\x6e\144"); }, "\x72\x65\155\157\164\x65\55\142\x61\x63\x6b\165\x70\72\x63\x72\145\x61\x74\x65" => function () use ($container) {
                        return $container->get("\x41\x70\160\x5c\x43\x6f\x6d\155\141\x6e\144\134\122\145\x6d\157\x74\145\x42\141\143\153\x75\x70\x43\162\x65\x61\164\x65\x43\x6f\155\x6d\x61\x6e\x64"); }, "\x74\145\x73\164\72\x74\x65\163\164" => function () use ($container) {
                        return $container->get("\x41\160\160\x5c\x43\x6f\x6d\155\x61\x6e\x64\134\x54\x65\x73\164\x43\x6f\x6d\155\141\x6e\x64"); }];
                goto fd9f3;
            default:
                $commands = ["\x64\x62\x3a\145\x78\x70\157\x72\164" => function () use ($container) {
                    return $container->get("\101\x70\160\134\103\x6f\x6d\x6d\141\156\144\x5c\104\x61\164\141\142\141\163\x65\x45\x78\160\x6f\162\164\x43\x6f\155\155\x61\x6e\x64"); }, "\144\x62\72\151\x6d\x70\157\x72\164" => function () use ($container) {
                        return $container->get("\x41\160\x70\x5c\103\157\155\x6d\x61\x6e\x64\134\104\x61\164\141\x62\141\163\x65\111\x6d\x70\157\162\x74\x43\x6f\155\x6d\141\x6e\144"); }, "\x73\171\x73\x74\x65\x6d\72\x70\145\162\x6d\151\x73\x73\151\x6f\156\x73\x3a\162\x65\163\145\164" => function () use ($container) {
                        return $container->get("\x41\160\x70\x5c\x43\x6f\x6d\x6d\141\x6e\x64\x5c\x53\x79\163\164\145\x6d\120\145\x72\x6d\151\x73\163\151\x6f\156\x73\x52\145\x73\x65\164\103\157\155\x6d\141\156\x64"); }, "\x76\141\x72\156\151\x73\150\55\x63\x61\x63\150\145\72\x70\x75\162\x67\x65" => function () use ($container) {
                        return $container->get("\101\x70\x70\134\103\157\155\x6d\x61\156\144\134\x56\x61\x72\156\151\x73\150\x43\141\143\150\145\120\x75\x72\147\145\103\x6f\155\155\141\x6e\144"); }];
                goto fd9f3;
        }
        goto b6246;
        b6246:
        C33a0:
        goto Dc719;
        c47dd:
    }
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        goto B8d0b;
        B8d0b:
        $this->registerCommands();
        goto b1f79;
        b1f79:
        $this->setApplicationName();
        goto Cd273;
        Cd273:
        return parent::doRun($input, $output);
        goto Ce573;
        Ce573:
    }
    private function setApplicationName(): void
    {
        $this->setName(self::APPLICATION_NAME);
    }
    protected function getDefaultCommands(): array
    {
        $listCommand = new ListCommand();
        return [$listCommand];
    }
    public function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }
}