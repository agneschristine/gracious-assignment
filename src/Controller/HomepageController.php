<?php
namespace App\Controller;

use App\Service\CharacterService;
use App\Service\EpisodeService;
use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{
    /**
     * @var LocationService
     */
    private $locationService;

    /**
     * @var CharacterService
     */
    private $characterService;

    /**
     * @var EpisodeService
     */
    private $episodeService;

    /**
     * DefaultController constructor.
     * @param LocationService $locationService
     * @param CharacterService $characterService
     * @param EpisodeService $episodeService
     */
    public function __construct(
        LocationService $locationService,
        CharacterService $characterService,
        EpisodeService $episodeService
    ) {
        $this->locationService = $locationService;
        $this->characterService = $characterService;
        $this->episodeService = $episodeService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $locations = $this->locationService->getLocations();
        $episodes = $this->episodeService->getEpisodes();

        return $this->render('index.html.twig', [
            'dimensions' => $this->locationService->getDimensions($locations),
            'locations' => $locations,
            'episodes' => $episodes
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayCharactersByDimension(Request $request)
    {
        $characters = $this->characterService->getCharactersByDimension($request->query->all());

        return $this->render('character.html.twig', [
            'characters' => $characters
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayCharactersByLocation(Request $request)
    {
        $locationId = $request->query->getInt('location');
        $characters = $this->characterService->getCharactersByLocation($locationId);

        return $this->render('character.html.twig', [
            'characters' => $characters
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayCharactersByEpisode(Request $request)
    {
        $episodeId = $request->query->getInt('episode');
        $characters = $this->characterService->getCharactersByEpisode($episodeId);

        return $this->render('character.html.twig', [
            'characters' => $characters
        ]);
    }
}
