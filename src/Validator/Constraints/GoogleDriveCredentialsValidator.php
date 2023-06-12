<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Backup\Rclone\GoogleDriveConfigTemplate;
use App\Backup\Rclone\ConfigBuilder as RcloneConfigBuilder;
use App\Backup\Rclone;

class GoogleDriveCredentialsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $email = trim($form->get('email')->getData());
            $serviceAccount = trim($form->get('serviceAccount')->getData());
            if (false === empty($email) && false === empty($serviceAccount)) {
                try {
                    $tmpConfigFile = tmpfile();
                    $tmpServiceAccountFile = tmpfile();
                    $configFile = stream_get_meta_data($tmpConfigFile)['uri'];
                    $serviceAccountFile = stream_get_meta_data($tmpServiceAccountFile)['uri'];
                    $rcloneConfigTemplate = new GoogleDriveConfigTemplate();
                    $rcloneConfigTemplate->setSetting('service_account_file', $serviceAccountFile);
                    $rcloneConfigBuilder = new RcloneConfigBuilder($rcloneConfigTemplate);
                    $rcloneConfig = $rcloneConfigBuilder->build();
                    file_put_contents($configFile, $rcloneConfig);
                    file_put_contents($serviceAccountFile, $serviceAccount);
                    $rclone = new Rclone();
                    $rclone->addFlag('--drive-impersonate', $email);
                    $rclone->setConfigFile($configFile);
                    $rclone->lsJson('/', false);
                } catch (\Exception $e) {
                    throw $e;
                } finally {
                    if (true === isset($configFile)) {
                        @unlink($configFile);
                    }
                    if (true === isset($serviceAccountFile)) {
                        @unlink($serviceAccountFile);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
