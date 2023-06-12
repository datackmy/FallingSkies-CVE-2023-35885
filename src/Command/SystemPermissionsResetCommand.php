<?php
namespace App\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Command\Command as BaseCommand;
use App\Entity\Manager\SiteManager as SiteEntityManager;
use App\Entity\Manager\SshUserManager as SshUserEntityManager;
use App\System\CommandExecutor;
use App\System\Command\CheckIfFileExistsCommand;
use App\System\Command\ChownCommand;
use App\System\Command\FindChmodCommand;
class SystemPermissionsResetCommand extends BaseCommand
{
    private SiteEntityManager $siteEntityManager;
    private SshUserEntityManager $sshUserEntityManager;
    public function __construct(SiteEntityManager $siteEntityManager, SshUserEntityManager $sshUserEntityManager)
    {
        goto Af8b1;
        B4114:
        parent::__construct();
        goto f90d5;
        e0250:
        $this->sshUserEntityManager = $sshUserEntityManager;
        goto B4114;
        Af8b1:
        $this->siteEntityManager = $siteEntityManager;
        goto e0250;
        f90d5:
    }
    protected function configure(): void
    {
        goto D1ead;
        B2a4c:
        $this->addOption("\146\x69\x6c\x65\163", null, InputOption::VALUE_REQUIRED, false);
        goto A77b3;
        A77b3:
        $this->addOption("\x70\141\164\150", null, InputOption::VALUE_REQUIRED, false);
        goto a2574;
        D1ead:
        $this->setName("\x73\x79\163\x74\145\x6d\x3a\x70\x65\162\155\151\163\x73\x69\x6f\x6e\x73\x3a\162\x65\x73\x65\x74");
        goto Fdc4c;
        Fdc4c:
        $this->setDescription("\143\154\160\143\x74\154\40\163\171\x73\164\145\x6d\x3a\160\145\x72\155\151\x73\x73\x69\157\x6e\x73\x3a\162\145\163\x65\x74\40\x2d\55\x64\151\x72\x65\x63\164\157\162\151\x65\163\x3d\x37\67\60\x20\x2d\x2d\146\x69\154\145\163\75\66\x36\60\x20\x2d\x2d\160\x61\x74\150\x3d\x2e");
        goto f8cd8;
        f8cd8:
        $this->addOption("\x64\x69\x72\145\143\164\x6f\162\151\145\x73", null, InputOption::VALUE_REQUIRED, false);
        goto B2a4c;
        a2574:
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            goto A921b;
            b270a:
            $chmodFiles = (string) $input->getOption("\x66\151\x6c\x65\163");
            goto Fc026;
            b2b32:
            goto Ef35c;
            goto E5697;
            B9e06:
            $chmodDirectories = (string) $input->getOption("\144\151\162\145\x63\x74\x6f\162\151\145\x73");
            goto b270a;
            Cc65d:
            $commandExecutor->execute($chownCommand, 1800);
            goto C37ae;
            Ffd73:
            Ef35c:
            goto bf932;
            ab4bb:
            $user = $sshUserEntity->getUserName();
            goto A8406;
            e8c18:
            $chownCommand = new ChownCommand();
            goto D955f;
            d054b:
            $path = sprintf("\45\163\x2f\45\163", rtrim(getcwd(), "\57"), $path);
            goto Cd12f;
            Fc026:
            if ("\56" == $path) {
                goto b1943;
            }
            goto f7660;
            c3a32:
            E7562:
            goto D83d0;
            bf932:
            if (!(false === empty($allowedDirectories) && false === is_null($user) && false === is_null($group))) {
                goto C22f1;
            }
            goto bcb0f;
            f8207:
            $group = null;
            goto F906e;
            Cd12f:
            Bbddb:
            goto aa9f3;
            f7660:
            if (!(false === str_starts_with($path, "\x2f\x68\x6f\x6d\x65\57"))) {
                goto Bbddb;
            }
            goto d054b;
            Dc3d6:
            foreach ($allowedDirectories as $allowedDirectory) {
                goto ab74a;
                ead1d:
                d831f:
                goto Bffc6;
                ab74a:
                if (!(true === str_starts_with($path, $allowedDirectory))) {
                    goto d831f;
                }
                goto e8282;
                Bffc6:
                Ff33e:
                goto E5553;
                B9d68:
                goto f9af1;
                goto ead1d;
                e8282:
                $isValidDirectory = true;
                goto B9d68;
                E5553:
            }
            goto b3009;
            f05df:
            $chmodCommand = new FindChmodCommand();
            goto c17a8;
            E32ba:
            $chownCommand->setFile($path);
            goto C6805;
            D83d0:
            $systemUserName = $_SERVER["\123\125\104\117\137\x55\x53\105\x52"] ?? null;
            goto a54d8;
            d2fc0:
            $commandExecutor = new CommandExecutor();
            goto A1ff7;
            C6805:
            $chownCommand->setRecursive(true);
            goto f05df;
            A8986:
            E7f90:
            goto A1bec;
            A1ff7:
            try {
                goto cdb35;
                A6bde:
                $checkIfFileExistsCommand->setFile($path);
                goto bdc2d;
                cdb35:
                $checkIfFileExistsCommand = new CheckIfFileExistsCommand();
                goto A6bde;
                bdc2d:
                $commandExecutor->execute($checkIfFileExistsCommand);
                goto Bf333;
                Bf333:
            } catch (\Exception $e) {
                goto a3442;
                Cd705:
                $output->writeln(sprintf("\74\x65\162\162\x6f\162\76\45\163\74\x2f\145\162\162\157\x72\x3e", $errorMessage));
                goto aa65f;
                a3442:
                $errorMessage = sprintf("\120\x61\164\x68\40\42\45\163\42\40\x64\x6f\145\163\40\156\157\x74\x20\145\x78\151\163\164\56", $path);
                goto Cd705;
                aa65f:
                return BaseCommand::FAILURE;
                goto c2b71;
                c2b71:
            }
            goto e8c18;
            E5697:
            ebc95:
            goto ecf9e;
            ecf9e:
            $allowedDirectories[] = sprintf("\x2f\x68\x6f\155\145\57\x25\163\x2f", $siteEntity->getUser());
            goto f3667;
            ac169:
            $chmodCommand->setFileChmod($chmodFiles);
            goto ccdbc;
            bcb0f:
            $isValidDirectory = false;
            goto Dc3d6;
            a6aa3:
            b1943:
            goto C529d;
            f36dd:
            if (!(false === is_null($sshUserEntity))) {
                goto d7a43;
            }
            goto b9063;
            C529d:
            $path = sprintf("\x25\163\57", rtrim(getcwd(), "\x2f"));
            goto c3a32;
            E59d3:
            $sshUserEntity = $this->sshUserEntityManager->findOneByUserName($systemUserName);
            goto f36dd;
            f3667:
            $user = $siteEntity->getUser();
            goto d3774;
            d3774:
            $group = $user;
            goto Ffd73;
            C88a7:
            $chownCommand->setGroup($group);
            goto E32ba;
            eab4d:
            $allowedDirectories = [sprintf("\x2f\150\157\x6d\145\57\45\163\57", $siteEntity->getUser()), sprintf("\x2f\150\x6f\x6d\x65\57\45\x73\x2f", $sshUserEntity->getUserName())];
            goto Cd358;
            Ae7a5:
            $user = null;
            goto f8207;
            A921b:
            $path = (string) $input->getOption("\x70\141\164\x68");
            goto B9e06;
            C37ae:
            $commandExecutor->execute($chmodCommand, 1800);
            goto Cdf01;
            afbb6:
            $siteEntity = $this->siteEntityManager->findOneByUser($systemUserName);
            goto ac1c2;
            e099f:
            return BaseCommand::SUCCESS;
            goto d0c4b;
            ccdbc:
            $chmodCommand->setFile($path);
            goto Cc65d;
            a54d8:
            $allowedDirectories = [];
            goto Ae7a5;
            Cd358:
            d7a43:
            goto b2b32;
            c9e64:
            Fd75d:
            goto e099f;
            b9063:
            $siteEntity = $sshUserEntity->getSite();
            goto ab4bb;
            c17a8:
            $chmodCommand->setDirectoryChmod($chmodDirectories);
            goto ac169;
            F906e:
            if (!(false === is_null($systemUserName))) {
                goto Fd75d;
            }
            goto afbb6;
            A8406:
            $group = $siteEntity->getUser();
            goto eab4d;
            aa9f3:
            goto E7562;
            goto a6aa3;
            ac1c2:
            if (false === is_null($siteEntity)) {
                goto ebc95;
            }
            goto E59d3;
            F172c:
            if (!(true === $isValidDirectory)) {
                goto E7f90;
            }
            goto d2fc0;
            A1bec:
            C22f1:
            goto c9e64;
            b3009:
            f9af1:
            goto F172c;
            D955f:
            $chownCommand->setUser($user);
            goto C88a7;
            Cdf01:
            $output->writeln("\x3c\x69\156\x66\157\x3e\120\145\162\155\151\163\x73\151\157\156\163\40\x68\141\x76\x65\x20\x62\x65\x65\156\40\x72\145\x73\145\x74\x2e\74\x2f\x69\x6e\x66\157\76");
            goto A8986;
            d0c4b:
        } catch (\Exception $e) {
            goto C5dd0;
            E437e:
            $output->writeln(sprintf("\74\x65\x72\x72\x6f\x72\76\x25\x73\74\57\145\162\162\157\x72\x3e", $errorMessage));
            goto Cd235;
            C5dd0:
            $logger = $this->getLogger();
            goto E49ae;
            Fe3b2:
            $errorMessage = $e->getMessage();
            goto E437e;
            Cd235:
            return BaseCommand::FAILURE;
            goto F9422;
            E49ae:
            $logger->exception($e);
            goto Fe3b2;
            F9422:
        }
    }
}