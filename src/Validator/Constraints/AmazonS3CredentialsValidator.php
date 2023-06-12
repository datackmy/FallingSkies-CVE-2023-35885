<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Aws\Credentials\Credentials as AwsCredentials;
use Aws\S3\S3Client;

class AmazonS3CredentialsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $region = $form->get('region')->getData();
            $accessKey = $form->get('accessKey')->getData();
            $secretAccessKey = $form->get('secretAccessKey')->getData();
            if (false === empty($accessKey) && false === empty($secretAccessKey)) {
                $credentials = new AwsCredentials($accessKey, $secretAccessKey);
                $s3Client = new S3Client([
                    'version'     => 'latest',
                    'region'      => $region,
                    'credentials' => $credentials
                ]);
                $s3Client->listBuckets();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
