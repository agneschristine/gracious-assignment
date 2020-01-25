<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;

class CharacterResponse
{
    /**
     * @var Character[]|Collection
     * @Serializer\Type("ArrayCollection<App\Entity\Character>")
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return Character[]|Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Character[]|Collection $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

}
