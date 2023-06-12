<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BasicAuthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $basicAuthEntity = $value;
        $isActive = $basicAuthEntity->getIsActive();
        if (true === $isActive) {
            $userName = $basicAuthEntity->getUserName();
            $password = $basicAuthEntity->getPassword();
            $whitelistedIps = $basicAuthEntity->getWhitelistedIps();
            if (true === empty($userName)) {
                $this->context->buildViolation($constraint->message)->atPath('userName')->addViolation();
            }
            if (true === empty($password)) {
                $this->context->buildViolation($constraint->message)->atPath('password')->addViolation();
            }
            if (false === empty($whitelistedIps)) {
                $whitelistedIps = explode(',', $whitelistedIps);
                foreach ($whitelistedIps as $ip) {
                    $ipAddress = $ip;
                    $ipParts = explode( '/', $ipAddress);
                    $ip = $ipParts[0] ?? '';
                    $isIpv6 = substr_count($ipAddress, ':') ? true : false;
                    $isValidIp = false;
                    if (true === $isIpv6) {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                            $isValidIp = true;
                        }
                    } else {
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            $isValidIp = true;
                        }
                    }
                    if (false === $isValidIp) {
                        $this->context->buildViolation($constraint->message)->atPath('whitelistedIps')->addViolation();
                        break;
                    }
                }
            }
        }
    }
}
