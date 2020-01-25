<?php
namespace spec\App\Service;

use App\Entity\LocationResponse;
use App\Entity\Location;
use App\Service\Client;
use App\Service\LocationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;
use PhpSpec\ObjectBehavior;

class LocationServiceSpec extends ObjectBehavior
{
    public function let(
        Client $client,
        SerializerInterface $serializer
    ) {
        $this->beConstructedWith($client, $serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LocationService::class);
    }

    public function it_gets_dimensions(
        Location $location1,
        Location $location2
    ) {
        $locations = new ArrayCollection([
            $location1->getWrappedObject(),
            $location2->getWrappedObject(),
        ]);

        $location1->getDimension()->willReturn('Earth');
        $location2->getDimension()->willReturn('unknown');

        $this->getDimensions($locations)->shouldReturn(['Earth', 'unknown']);
    }

    public function it_gets_locations(
        $client,
        $serializer,
        LocationResponse $locationResponse,
        Collection $results
    ) {
        $client->getLocations([], [])->willReturn('{}');
        $serializer->deserialize(
            '{}',
            LocationResponse::class,
            'json'
        )->willReturn($locationResponse);

        $locationResponse->getResults()->willReturn($results);

        $this->getLocations()->shouldReturn($results);
    }

    public function it_gets_location(
        $client,
        $serializer,
        Location $location
    ) {
        $client->getLocations([1], [])->willReturn('{}');
        $serializer->deserialize(
            '{}',
            Location::class,
            'json'
        )->willReturn($location);

        $this->getLocation([1])->shouldReturn($location);
    }
}
