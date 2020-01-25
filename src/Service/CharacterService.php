<?php
namespace App\Service;

use App\Entity\Character;
use App\Entity\Location;
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
     * @param array $characterIds
     * @param array $uris
     * @return array
     */
    private function getCharacterIds(array $characterIds, array $uris): array
    {
        foreach ($uris as $uri) {
            array_push($characterIds,preg_replace('/[^0-9]/', '', $uri));
        }

        return $characterIds;
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function getCharactersByDimension(array $parameters = []): array
    {
        $locations = $this->locationService->getLocations([], $parameters);
        $residents = $locations->map(function(Location $location) {
            return $location->getResidents();
        });

        $characterIds = [];
        foreach ($residents as $uris) {
            if (empty($uris)) {
                break;
            }

            $characterIds = $this->getCharacterIds($characterIds, $uris);
        }

        if (! empty($characterIds)) {
            return $this->getCharacters($characterIds, 'array<App\Entity\Character>');
        }

        return [];
    }

    /**
     * @param int $locationId
     * @return array
     */
    public function getCharactersByLocation(int $locationId): array
    {
        $location = $this->locationService->getLocation([$locationId]);
        $residents = $location->getResidents();

        $characterIds = [];
        if (! empty($residents)) {
            $characterIds = $this->getCharacterIds($characterIds, $residents);
            $type = Character::class;
            if (count($characterIds) > 1) {
                $type = 'array<App\Entity\Character>';
            }

            return $this->getCharacters($characterIds, $type);
        }

        return [];
    }

    /**
     * @param int $episodeId
     * @return array
     */
    public function getCharactersByEpisode(int $episodeId): array
    {
        $episode = $this->episodeService->getEpisode($episodeId);
        $characters = $episode->getCharacters();

        $characterIds = [];
        if (! empty($characters)) {
            $characterIds = $this->getCharacterIds($characterIds, $characters);
            $type = Character::class;
            if (count($characterIds) > 1) {
                $type = 'array<App\Entity\Character>';
            }

            return $this->getCharacters($characterIds, $type);
        }

        return [];
    }
}
