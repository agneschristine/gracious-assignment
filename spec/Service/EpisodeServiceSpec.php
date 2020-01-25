<?php
namespace spec\App\Service;

use App\Entity\Episode;
use App\Entity\EpisodeResponse;
use App\Service\Client;
use App\Service\EpisodeService;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\SerializerInterface;
use PhpSpec\ObjectBehavior;

class EpisodeServiceSpec extends ObjectBehavior
{
    public function let(
        Client $client,
        SerializerInterface $serializer
    ) {
        $this->beConstructedWith($client, $serializer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EpisodeService::class);
    }

    public function it_gets_episodes(
        $client,
        $serializer,
        EpisodeResponse $episodeResponse,
        Collection $results
    ) {
        $client->getEpisodes()->willReturn('{}');
        $serializer->deserialize(
            '{}',
            EpisodeResponse::class,
            'json'
        )->willReturn($episodeResponse);

        $episodeResponse->getResults()->willReturn($results);

        $this->getEpisodes()->shouldReturn($results);
    }

    public function it_gets_episode(
        $client,
        $serializer,
        Episode $episode
    ) {
        $client->getEpisodes(1)->willReturn('{}');
        $serializer->deserialize(
            '{}',
            Episode::class,
            'json'
        )->willReturn($episode);

        $this->getEpisode(1)->shouldReturn($episode);
    }
}
