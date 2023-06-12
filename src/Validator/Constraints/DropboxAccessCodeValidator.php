<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Backup\Dropbox\AccessCodeValidator as DropboxAccessValidator;
use App\Backup\Rclone\DropboxConfigTemplate;
use App\Backup\Rclone\ConfigBuilder as RcloneConfigBuilder;
use App\Backup\Rclone;

class DropboxAccessCodeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $request = $constraint->getRequest();
            $session = $request->getSession();
            $form = $this->context->getRoot();
            $accessCode = $form->get('accessCode')->getData();
            $token = $session->get('token');
            if (false === empty($accessCode) && true === empty($token)) {
                $accessCodeValidator = new DropboxAccessValidator();
                $isAccessCodeValid = $accessCodeValidator->isValid($accessCode);
                if (true === $isAccessCodeValid) {
                    try {
                        $token = $accessCodeValidator->getToken();
                        $refreshToken = $accessCodeValidator->getRefreshToken();
                        $tmpFile = tmpfile();
                        $tmpConfigFile = stream_get_meta_data($tmpFile)['uri'];
                        $rcloneConfigTemplate = new DropboxConfigTemplate();
                        $rcloneConfigTemplate->setToken($token);
                        $rcloneConfigBuilder = new RcloneConfigBuilder($rcloneConfigTemplate);
                        $rcloneConfig = $rcloneConfigBuilder->build();
                        file_put_contents($tmpConfigFile, $rcloneConfig);
                        $rclone = new Rclone();
                        $rclone->setConfigFile($tmpConfigFile);
                        $rclone->lsJson();
                        $session->set('token', $token);
                        $session->set('refreshToken', $refreshToken);
                    } catch (\Exception $e) {
                        throw $e;
                    } finally {
                        if (true === isset($tmpConfigFile)) {
                            @unlink($tmpConfigFile);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->context->buildViolation($errorMessage)->addViolation();
        }
    }
}
