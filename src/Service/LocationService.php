<?php
namespace App\Service;

use App\Entity\Location;
use App\Entity\LocationResponse;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;

class LocationService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Service constructor.
     * @param Client $client
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Client $client,
        SerializerInterface $serializer
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @param Collection $locations
     * @return array
     */
    public function getDimensions(Collection $locations): array
    {
        $dimensions = $locations->map(function(Location $location) {
            return $location->getDimension();
        })->toArray();

        return array_unique($dimensions);
    }

    /**
     * @param array $ids
     * @param array $parameters
     * @return Collection
     */
    public function getLocations(array $ids = [], array $parameters = []): Collection
    {
        $response = $this->serializer->deserialize(
            $this->client->getLocations($ids, $parameters),
            LocationResponse::class,
            'json'
        );

        return $response->getResults();
    }

    /**
     * @param array $ids
     * @param array $parameters
     * @return Location
     */
    public function getLocation(array $ids = [], array $parameters = []): Location
    {
        return $this->serializer->deserialize(
            $this->client->getLocations($ids, $parameters),
            Location::class,
            'json'
        );
    }
}
