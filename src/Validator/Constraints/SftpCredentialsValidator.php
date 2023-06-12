<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\System\CommandExecutor;
use App\System\Command\RclonePasswordObscureCommand;
use App\Backup\Rclone\SftpConfigTemplate;
use App\Backup\Rclone\ConfigBuilder as RcloneConfigBuilder;
use App\Backup\Rclone;

class SftpCredentialsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $authenticationMethod = $form->get('authenticationMethod')->getData();
            $host = $form->get('host')->getData();
            $user = $form->get('user')->getData();
            $password = $form->get('password')->getData();
            if ('password' == $authenticationMethod && true === empty($password)) {
                return;
            }
            $keyFile = $form->get('keyFile')->getData();
            $port = $form->get('port')->getData();
            $storageDirectory = $form->get('storageDirectory')->getData();
            if (false === empty($host) && false === empty($user)) {
                try {
                    $commandExecutor = new CommandExecutor();
                    $remotePath = sprintf('%s/', rtrim($storageDirectory, '/'));
                    $tmpFile = tmpfile();
                    $tmpConfigFile = stream_get_meta_data($tmpFile)['uri'];
                    $rcloneConfigTemplate = new SftpConfigTemplate();
                    $rcloneConfigTemplate->setSetting('host', $host);
                    $rcloneConfigTemplate->setSetting('user', $user);
                    if ('password' == $authenticationMethod) {
                        $rclonePasswordObscureCommand = new RclonePasswordObscureCommand();
                        $rclonePasswordObscureCommand->setPassword($password);
                        $commandExecutor->execute($rclonePasswordObscureCommand);
                        $obscuredPassword = $rclonePasswordObscureCommand->getObscuredPassword();
                        $rcloneConfigTemplate->setSetting('pass', $obscuredPassword);
                    } else {
                        $rcloneConfigTemplate->setSetting('key_file', $keyFile);
                    }
                    $rcloneConfigTemplate->setSetting('port', $port);
                    $rcloneConfigBuilder = new RcloneConfigBuilder($rcloneConfigTemplate);
                    $rcloneConfig = $rcloneConfigBuilder->build();
                    file_put_contents($tmpConfigFile, $rcloneConfig);
                    $rclone = new Rclone();
                    $rclone->setConfigFile($tmpConfigFile);
                    $rclone->lsJson($remotePath);
                } catch (\Exception $e) {
                    throw $e;
                } finally {
                    if (true === isset($tmpConfigFile)) {
                        @unlink($tmpConfigFile);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($e->getMessage())->addViolation();
        }
    }
}
