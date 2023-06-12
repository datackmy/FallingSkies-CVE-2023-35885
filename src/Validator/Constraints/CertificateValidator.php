<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Site\Ssl\Certificate;
use App\Site\Ssl\CertificateParser;

class CertificateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $certificateEntity = $value;
            $privateKey = $certificateEntity->getPrivateKey();
            $certificate = $certificateEntity->getCertificate();
            if (true === empty($privateKey) && true === empty($certificate)) {
                return;
            }
            $certificateChain = $certificateEntity->getCertificateChain();
            $isPrivateKeyValid = openssl_x509_check_private_key($certificate, $privateKey);
            if (true === $isPrivateKeyValid) {
                if (false === empty($certificateChain)) {
                    preg_match_all('/-{5}BEGIN\sCERTIFICATE-{5}(.*?)-{5}END\sCERTIFICATE-{5}/s', $certificateChain, $matches);
                    if (true === isset($matches[0]) && count($matches[0])) {
                        try {
                            $intermediateCertificates = $matches[0];
                            $certificateParser = new CertificateParser();
                            $mainCertificate = new Certificate();
                            $mainCertificate->setPrivateKey($privateKey);
                            $mainCertificate->setCertificate($certificate);
                            $mainCertificate->setCertificateChain($certificateChain);
                            $mainParsedCertificate = $certificateParser->parse($mainCertificate);
                            $parsedIntermediateCertificate = [];
                            if (count($intermediateCertificates)) {
                                foreach ($intermediateCertificates as $intermediateCertificate) {
                                    $certificate = new Certificate();
                                    $certificate->setCertificate($intermediateCertificate);
                                    $parsedIntermediateCertificate[] = $certificateParser->parse($certificate);
                                }
                                $prevIssuer = $mainParsedCertificate->getIssuerList();
                                while (false === empty($parsedIntermediateCertificate)) {
                                    $certificate = array_shift($parsedIntermediateCertificate);
                                    $subjectList = $certificate->getSubjectList();
                                    if ($prevIssuer !== $subjectList) {
                                        throw new \Exception($constraint->message);
                                    }
                                    $prevIssuer = $certificate->getIssuerList();
                                }
                            }
                        } catch (\Exception $e) {
                            $this->context->buildViolation($constraint->message)->atPath('certificateChain')->addViolation();
                        }
                    }
                }
            } else {
                $this->context->buildViolation($constraint->messagePrivateKeyDoesNotMatchCertificate)->atPath('privateKey')->addViolation();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
