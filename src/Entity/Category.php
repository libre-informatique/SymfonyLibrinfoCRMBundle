<?php

namespace Librinfo\CRMBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;
use Blast\BaseEntitiesBundle\Entity\Traits\NestedTreeable;

/**
 * Category
 */
class Category
{

    use BaseEntity,
        Nameable,
        NestedTreeable
    ;

    /**
     * @var Collection
     */
    private $organisms;


    /**
     * Constructor
     */
    public function __construct()
    {
        //init NesteTreeable treeChildren Collection
        $this->initCollections();
        $this->organisms = new ArrayCollection();
    }

    /**
     * Add organism
     *
     * @param Organism $organism
     *
     * @return Category
     */
    public function addOrganism(Organism $organism)
    {
        $this->organisms[] = $organism;

        return $this;
    }

    /**
     * Remove organism
     *
     * @param Organism $organism
     */
    public function removeOrganism(Organism $organism)
    {
        $this->organisms->removeElement($organism);
    }

    /**
     * Get organisms
     *
     * @return Collection
     */
    public function getOrganisms()
    {
        return $this->organisms;
    }
}