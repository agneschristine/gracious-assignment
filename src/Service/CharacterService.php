<?php
namespace App\Service;

use App\Entity\Character;
use App\Entity\Location;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;

class CharacterService
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
     * @var LocationService
     */
    private $locationService;

    /**
     * @var EpisodeService
     */
    private $episodeService;

    /**
     * Service constructor.
     * @param Client $client
     * @param SerializerInterface $serializer
     * @param LocationService $locationService
     * @param EpisodeService $episodeService
     */
    public function __construct(
        Client $client,
        SerializerInterface $serializer,
        LocationService $locationService,
        EpisodeService $episodeService
    ) {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->locationService = $locationService;
        $this->episodeService = $episodeService;
    }

    /**
     * @param array $ids
     * @param string $type
     * @return array|Character
     */
    private function getCharacters(array $ids = [], string $type)
    {
        $response = $this->serializer->deserialize(
            $this->client->getCharacters($ids),
            $type,
            'json'
        );

        return $response;
    }

    /**
     * @param array $uris
     * @return array
     */
    private function getCharacterIds(array $uris): array
    {
        $characterIds = [];

        foreach ($uris as $uri) {
            array_push($characterIds,preg_replace('/[^0-9]/', '', $uri));
        }

        return $characterIds;
    }

    /**
     * @param array $parameters
     * @return Collection
     */
    public function getCharactersByDimension(array $parameters = []): Collection
    {
        $characters = new ArrayCollection();

        $locations = $this->locationService->getLocations([], $parameters);
        $residents = $locations->map(function(Location $location) {
            return $location->getResidents();
        });

        $characterIds = [];
        foreach ($residents as $uris) {
            if (empty($uris)) {
                break;
            }

            $characterIds += $this->getCharacterIds($uris);
        }

        if (! empty($characterIds)) {
            $characters->add($this->getCharacters($characterIds, 'array<App\Entity\Character>'));
        }

        return $characters;
    }

    /**
     * @param int $locationId
     * @return Collection
     */
    public function getCharactersByLocation(int $locationId): Collection
    {
        $characters = new ArrayCollection();

        $location = $this->locationService->getLocation([$locationId]);
        $residents = $location->getResidents();

        if (! empty($residents)) {
            $characterIds = $this->getCharacterIds($residents);
            $type = Character::class;
            if (count($characterIds) > 1) {
                $type = 'array<App\Entity\Character>';
            }

            $characters->add($this->getCharacters($characterIds, $type));
        }
        return $characters;
    }

    /**
     * @param int $episodeId
     * @return Collection
     */
    public function getCharactersByEpisode(int $episodeId): Collection
    {
        $casts = new ArrayCollection();

        $episode = $this->episodeService->getEpisode($episodeId);
        $characters = $episode->getCharacters();

        if (! empty($characters)) {
            $characterIds = $this->getCharacterIds($characters);
            $type = Character::class;
            if (count($characterIds) > 1) {
                $type = 'array<App\Entity\Character>';
            }

            $casts->add($this->getCharacters($characterIds, $type));
        }

        return $casts;
    }
}
