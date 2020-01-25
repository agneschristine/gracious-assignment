<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;

class EpisodeResponse
{
    /**
     * @var Episode[]|Collection
     * @Serializer\Type("ArrayCollection<App\Entity\Episode>")
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    /**
     * @return Episode[]|Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Episode[]|Collection $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

}
