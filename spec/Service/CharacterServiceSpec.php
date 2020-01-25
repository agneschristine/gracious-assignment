<?php
namespace spec\App\Service;

use App\Entity\Character;
use App\Entity\Episode;
use App\Entity\Location;
use App\Service\CharacterService;
use App\Service\Client;
use App\Service\EpisodeService;
use App\Service\LocationService;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializerInterface;
use PhpSpec\ObjectBehavior;

class CharacterServiceSpec extends ObjectBehavior
{
    public function let(
        Client $client,
        SerializerInterface $serializer,
        LocationService $locationService,
        EpisodeService $episodeService
    ) {
        $this->beConstructedWith($client, $serializer, $locationService, $episodeService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CharacterService::class);
    }

    public function it_gets_character_by_dimension(
        $client,
        $serializer,
        $locationService,
        Location $location1,
        Location $location2,
        Character $character1,
        Character $character2
    ) {
        $parameters = ['dimension' => 'weird dimension'];
        $residents1 = [
            'https://rickandmortyapi.com/api/character/8',
            'https://rickandmortyapi.com/api/character/10',
        ];
        $residents2 = [
            'https://rickandmortyapi.com/api/character/1',
            'https://rickandmortyapi.com/api/character/25',
        ];

        $locations = new ArrayCollection([
            $location1->getWrappedObject(),
            $location2->getWrappedObject(),
        ]);

        $locationService->getLocations([], $parameters)->willReturn($locations);

        $location1->getResidents()->willReturn($residents1);
        $location2->getResidents()->willReturn($residents2);

        $client->getCharacters([8,10,1,25])->willReturn('[{}]');
        $serializer->deserialize(
            '[{}]',
            'array<App\Entity\Character>',
            'json'
        )->willReturn([$character1, $character2]);

        $this->getCharactersByDimension($parameters)->shouldReturn([$character1, $character2]);
    }

    public function it_gets_character_by_location(
        $client,
        $serializer,
        $locationService,
        Location $location,
        Character $character1,
        Character $character2
    ) {
        $residents = [
            'https://rickandmortyapi.com/api/character/8',
            'https://rickandmortyapi.com/api/character/10',
        ];

        $locationService->getLocation([3])->willReturn($location);

        $location->getResidents()->willReturn($residents);

        $client->getCharacters([8,10])->willReturn('[{}]');
        $serializer->deserialize(
            '[{}]',
            'array<App\Entity\Character>',
            'json'
        )->willReturn([$character1, $character2]);

        $this->getCharactersByLocation(3)->shouldReturn([$character1, $character2]);
    }

    public function it_gets_character_by_episode(
        $client,
        $serializer,
        $episodeService,
        Episode $episode,
        Character $character1,
        Character $character2
    ) {
        $characters = [
            'https://rickandmortyapi.com/api/character/8',
            'https://rickandmortyapi.com/api/character/10',
        ];

        $episodeService->getEpisode(3)->willReturn($episode);

        $episode->getCharacters()->willReturn($characters);

        $client->getCharacters([8,10])->willReturn('[{}]');
        $serializer->deserialize(
            '[{}]',
            'array<App\Entity\Character>',
            'json'
        )->willReturn([$character1, $character2]);

        $this->getCharactersByEpisode(3)->shouldReturn([$character1, $character2]);
    }
}
