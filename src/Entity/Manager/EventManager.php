<?php

namespace App\Entity\Manager;

use Doctrine\Common\Collections\Criteria;

class EventManager extends BaseManager
{
    public function findEventsByCriteria(Criteria $criteria)
    {
        return $this->repository->matching($criteria);
    }
}
