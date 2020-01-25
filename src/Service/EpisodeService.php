<?php
namespace App\Service;

use App\Entity\Episode;
use App\Entity\EpisodeResponse;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;

class EpisodeService
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
     * EpisodeService constructor.
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
     * @return Collection
     */
    public function getEpisodes(): Collection
    {
        $response = $this->serializer->deserialize(
            $this->client->getEpisodes(),
            EpisodeResponse::class,
            'json'
        );

        return $response->getResults();
    }

    /**
     * @param int $id
     * @return Episode
     */
    public function getEpisode(int $id): Episode
    {
        $response = $this->serializer->deserialize(
            $this->client->getEpisodes($id),
            Episode::class,
            'json'
        );

        return $response;
    }
}
