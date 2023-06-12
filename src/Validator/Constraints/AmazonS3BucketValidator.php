<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Aws\Credentials\Credentials as AwsCredentials;
use Aws\S3\S3Client;

class AmazonS3BucketValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $bucketName = $value;
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
                $result = $s3Client->listBuckets();
                $bucketFound = false;
                $buckets = (array)$result->get('Buckets');
                if (count($buckets)) {
                    foreach ($buckets as $bucket) {
                        if (true === isset($bucket['Name']) && $bucketName == $bucket['Name']) {
                            $bucketFound = true;
                            break;
                        }
                    }
                }
                if (false === $bucketFound) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
