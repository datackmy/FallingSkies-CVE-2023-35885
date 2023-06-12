<?php

namespace App\Validator\Constraints;

use App\Backup\Rclone;
use App\Backup\Rclone\ConfigBuilder as RcloneConfigBuilder;
use App\Backup\Rclone\DigitalOceanSpacesConfigTemplate;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DigitalOceanSpacesCredentialsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $space = $form->get('space')->getData();
            $spaceEndpoint = $form->get('spaceEndpoint')->getData();
            $accessKey = $form->get('accessKey')->getData();
            $secretAccessKey = $form->get('secretAccessKey')->getData();
            if (false === empty($accessKey) && false === empty($secretAccessKey)) {
                try {
                    $remotePath = sprintf('%s/', rtrim($space, '/'));
                    $tmpFile = tmpfile();
                    $tmpConfigFile = stream_get_meta_data($tmpFile)['uri'];
                    $rcloneConfigTemplate = new DigitalOceanSpacesConfigTemplate();
                    $rcloneConfigTemplate->setEndpoint($spaceEndpoint);
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
