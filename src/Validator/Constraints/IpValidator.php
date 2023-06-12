<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IpValidator extends ConstraintValidator
{
    const PATTERN = '/^(?:[\p{L}\-A-Za-z0-9ÄÖÜßäöü]+\.)+[A-Za-z]{2,14}$/iu';

    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }
        $ipAddress = (string)$value;
        $ipParts = explode( '/', $ipAddress);
        $ip = $ipParts[0] ?? '';
        $netmask = $ipParts[1] ?? '';
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
        if (true === $isValidIp && false === empty($netmask)) {
            $netmask = (int)$netmask;
            $isNetmaskValid = false;
            if ($netmask < 0) {
                $isNetmaskValid = false;
            }
            if (true === $isIpv6) {
                $isNetmaskValid = ($netmask <= 128);
            } else {
                $isNetmaskValid = ($netmask <= 32);
            }
            if (false === $isNetmaskValid) {
                $isValidIp = false;
            }
        }
        if (false === $isValidIp) {
            $this->context->addViolation($constraint->message);
        }
    }
}