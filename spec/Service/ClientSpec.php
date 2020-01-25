<?php
namespace spec\App\Service;

use App\Service\Client;
use Http\Message\RequestFactory;
use PhpSpec\ObjectBehavior;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class ClientSpec extends ObjectBehavior
{
    public function let(
        ClientInterface $httpClient,
        UriInterface $baseUri,
        RequestFactory $requestFactory
    ) {
        $this->beConstructedWith($httpClient, $baseUri, $requestFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_gets_characters(
        $baseUri,
        $requestFactory,
        $httpClient,
        RequestInterface $request,
        ResponseInterface $response,
        UriInterface $uri
    ) {
        $baseUri->getPath()->willReturn('https://rickandmortyapi.com/api');

        $baseUri->withPath('https://rickandmortyapi.com/api/character')->willReturn($uri);

        $requestFactory->createRequest('GET', $uri, [], '')->willReturn($request);

        $httpClient->sendRequest($request)->willReturn($response);

        $response->getBody()->willReturn('{}');
        $response->getStatusCode()->willReturn(200);

        $this->getCharacters()->shouldReturn('{}');
    }

    public function it_gets_locations(
        $baseUri,
        $requestFactory,
        $httpClient,
        RequestInterface $request,
        ResponseInterface $response,
        UriInterface $uri
    ) {
        $baseUri->getPath()->willReturn('https://rickandmortyapi.com/api');

        $baseUri->withPath('https://rickandmortyapi.com/api/location')->willReturn($uri);

        $requestFactory->createRequest('GET', $uri, [], '')->willReturn($request);

        $httpClient->sendRequest($request)->willReturn($response);

        $response->getBody()->willReturn('{}');
        $response->getStatusCode()->willReturn(200);

        $this->getLocations()->shouldReturn('{}');
    }

    public function it_gets_episodes(
        $baseUri,
        $requestFactory,
        $httpClient,
        RequestInterface $request,
        ResponseInterface $response,
        UriInterface $uri
    ) {
        $baseUri->getPath()->willReturn('https://rickandmortyapi.com/api');

        $baseUri->withPath('https://rickandmortyapi.com/api/episode')->willReturn($uri);

        $requestFactory->createRequest('GET', $uri, [], '')->willReturn($request);

        $httpClient->sendRequest($request)->willReturn($response);

        $response->getBody()->willReturn('{}');
        $response->getStatusCode()->willReturn(200);

        $this->getEpisodes()->shouldReturn('{}');
    }
}
