<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Backup\Rclone\WasabiConfigTemplate;
use App\Backup\Rclone\ConfigBuilder as RcloneConfigBuilder;
use App\Backup\Rclone;

class WasabiCredentialsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $bucket = $form->get('bucket')->getData();
            $region = $form->get('region')->getData();
            $accessKey = $form->get('accessKey')->getData();
            $secretAccessKey = $form->get('secretAccessKey')->getData();
            if (false === empty($accessKey) && false === empty($secretAccessKey)) {
                try {
                    $remotePath = sprintf('%s/', rtrim($bucket, '/'));
                    $tmpFile = tmpfile();
                    $tmpConfigFile = stream_get_meta_data($tmpFile)['uri'];
                    $rcloneConfigTemplate = new WasabiConfigTemplate();
                    $rcloneConfigTemplate->setRegion($region);
                    $rcloneConfigTemplate->setAccessKeyId($accessKey);
                    $rcloneConfigTemplate->setSecretAccessKey($secretAccessKey);
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
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
