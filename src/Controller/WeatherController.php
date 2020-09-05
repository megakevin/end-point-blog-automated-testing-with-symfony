<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\WeatherService;

class WeatherController extends AbstractController
{
    /**
     * @Route("/", name="weather", methods={"GET"})
     */
    public function index()
    {
        return $this->render('weather/index.html.twig');
    }

    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function search(Request $request, WeatherService $weatherService)
    {
        $formData = $request->request->all();

        $result = $weatherService->recordWeatherQuery($formData);

        if (!$result['success']) {
            return $this->render('weather/index.html.twig', [
                'validationErrors' => $result['errors'],
                'query' => $result['weatherQuery']
            ]);
        }

        return $this->redirectToRoute('show', [
            'city' => $result['weatherQuery']->getCity(),
            'state' => $result['weatherQuery']->getState()
        ]);
    }

    /**
     * @Route("/show/{city}/{state}", name="show", methods={"GET"})
     */
    public function show(string $city, string $state, WeatherService $weatherService)
    {
        $result = $weatherService->getCurrentWeather($city, $state);

        if (!$result['success']) {
            return $this->render('weather/index.html.twig', [
                'apiError' => true,
                'query' => $result['weatherQuery']
            ]);
        }

        return $this->render('weather/index.html.twig', [
            'weather' => $result['weather'],
            'query' => $result['weatherQuery']
        ]);
    }
}
