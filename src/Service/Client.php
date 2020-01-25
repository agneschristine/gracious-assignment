<?php
namespace App\Service;

use Http\Message\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Client
{
    const PATH_CHARACTER = '/character';

    const PATH_LOCATION = '/location';

    const PATH_EPISODE = '/episode';

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var UriInterface
     */
    private $baseUri;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * Client constructor.
     * @param ClientInterface $httpClient
     * @param UriInterface $baseUri
     * @param RequestFactory $requestFactory
     */
    public function __construct(
        ClientInterface $httpClient,
        UriInterface $baseUri,
        RequestFactory $requestFactory
    ) {
        $this->httpClient = $httpClient;
        $this->baseUri = $baseUri;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param array $ids
     * @return string
     */
    public function getCharacters(array $ids = []): string
    {
        $path = self::PATH_CHARACTER;
        if (! empty($ids)) {
            $path = sprintf('%s/%s', $path, implode (",", $ids));
        }
        $request = $this->makeRequest('GET', $path, '', []);
        return $this->sendRequest($request);
    }

    /**
     * @param array $ids
     * @param array $parameters
     * @return string
     */
    public function getLocations(array $ids = [], array $parameters = []): string
    {
        $path = self::PATH_LOCATION;
        if (! empty($ids)) {
            $path = sprintf('%s/%s', $path, implode (",", $ids));
        }
        $request = $this->makeRequest('GET', $path, '', $parameters);
        return $this->sendRequest($request);
    }

    /**
     * @param int $id
     * @param array $parameters
     * @return string
     */
    public function getEpisodes(int $id = 0, array $parameters = []): string
    {
        $path = self::PATH_EPISODE;
        if (! empty($id)) {
            $path = sprintf('%s/%s', $path, $id);
        }
        $request = $this->makeRequest('GET', $path, '', $parameters);
        return $this->sendRequest($request);
    }

    /**
     * @param UriInterface $uri
     * @param array $parameters
     * @return UriInterface
     */
    private function addParametersToUri(UriInterface $uri, array $parameters): UriInterface
    {
        $existingParams = [];
        parse_str($uri->getQuery(), $existingParams);
        $parameters = array_replace_recursive($existingParams, $parameters);

        $queryString = http_build_query($parameters);

        return $uri->withQuery($queryString);
    }

    /**
     * @param string $uriPath
     * @return UriInterface
     */
    private function makeUri(string $uriPath): UriInterface
    {
        return $this->baseUri->withPath($this->baseUri->getPath() . $uriPath);
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws \RuntimeException
     */
    private function processResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        if ($status !== 200) {
            throw new \RuntimeException(
                "Error processing the request: " . (string) $response->getBody(),
                $response->getStatusCode()
            );
        }
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    private function sendRequest(RequestInterface $request): string
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                $e->getMessage(),
                $e->getCode()
            );
        }

        $this->processResponse($response);

        return $response->getBody();
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $body
     * @param array $parameters
     * @return RequestInterface
     */
    private function makeRequest(
        string $method,
        string $path,
        string $body,
        array $parameters = null
    ): RequestInterface {
        $uri = $this->makeUri($path);
        if (! empty($parameters)) {
            $uri = $this->addParametersToUri($this->makeUri($path), $parameters);
        }

        return $this->requestFactory->createRequest($method, $uri, [], $body);
    }
}
