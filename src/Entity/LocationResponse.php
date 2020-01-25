<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;

class LocationResponse
{
    /**
     * @var Location[]|Collection
     * @Serializer\Type("ArrayCollection<App\Entity\Location>")
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return Location[]|Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Location[]|Collection $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

}
