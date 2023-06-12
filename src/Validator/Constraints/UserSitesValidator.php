<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\User;
use App\Entity\User as UserEntity;
use App\Entity\Manager\SiteManager as SiteEntityManager;

class UserSitesValidator extends ConstraintValidator
{
    private SiteEntityManager $siteEntityManager;

    public function __construct(SiteEntityManager $siteEntityManager)
    {
        $this->siteEntityManager = $siteEntityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        try {
            $parentForm = $this->context->getObject()->getParent();
            $userEntity = $parentForm->getData();
            $role = $userEntity->getRole();
            $userEntity->removeSites();
            if (UserEntity::ROLE_USER == $role) {
                $domainNames = trim($value);
                if (false === empty($domainNames)) {
                    $domainNames = array_map('trim', explode(',', $domainNames));
                    if (count($domainNames)) {
                        foreach ($domainNames as $domainName) {
                            $site = $this->siteEntityManager->findOneByDomainName($domainName);
                            if (false === is_null($site)) {
                                $userEntity->addSite($site);
                            }
                        }
                    }
                }
                if (0 == count($userEntity->getSites())) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
