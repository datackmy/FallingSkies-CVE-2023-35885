<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Manager\SiteManager as SiteEntityManager;
use App\Site\Parser\DomainName as DomainNameParser;
use App\Entity\Site as SiteEntity;

class UniqueDomainNameValidator extends ConstraintValidator
{
    private DomainNameParser $domainNameParser;
    private SiteEntityManager $siteEntityManager;

    public function __construct(DomainNameParser $domainNameParser,SiteEntityManager $siteEntityManager)
    {
        $this->domainNameParser = $domainNameParser;
        $this->siteEntityManager = $siteEntityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $contextObject = $this->context->getObject();
        if ($contextObject instanceof SiteEntity && true === is_null($contextObject->getId())) {
            $domainName = $contextObject->getDomainName();
        } else {
            $domainName = $value;
        }
        $resolvedDomainName = $this->domainNameParser->resolveDomainName($domainName);
        $subdomain = $resolvedDomainName->subDomain()->toString();
        $subdomain = (false === empty($subdomain) ? $subdomain : null);
        $site = $this->siteEntityManager->findOneByDomainName($domainName);
        if (true === is_null($subdomain) && true === is_null($site)) {
            $domainName = sprintf('www.%s', $domainName);
            $site = $this->siteEntityManager->findOneByDomainName($domainName);
        }
        if (false === is_null($site)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
