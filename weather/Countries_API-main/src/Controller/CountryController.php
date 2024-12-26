<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CountryController extends AbstractController
{
    #[Route("/", name: "app_country")]
    public function index(): Response
    {
        $ch = curl_init("https://restcountries.com/v3.1/all");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->addFlash(
                "error",
                "Error retrieving countries: " . curl_error($ch)
            );
        }
        curl_close($ch);

        $data = json_decode($result, true);
        return $this->render("country/index.html.twig", [
            "data" => $data,
        ]);
    }

    #[Route("/weather", name: "app_weather", methods: ["GET", "POST"])]
    public function weather(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($request->isMethod("POST")) {
            $city = $request->request->get("city", "default_city");

            $accessToken = "a7b756eaee0a4fde87e93444242205";
            $url =
                "https://api.weatherapi.com/v1/current.json?key={$accessToken}&q=" .
                urlencode($city);

            $ch2 = curl_init($url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

            $result2 = curl_exec($ch2);
            if (curl_errno($ch2)) {
                $this->addFlash(
                    "error",
                    "Error retrieving weather: " . curl_error($ch2)
                );
            }
            curl_close($ch2);

            $weatherData = json_decode($result2, true);

            // Store the weather data in the session
            $session->set("weatherData", $weatherData);
            $session->set("city", $city);

            // Redirect to a new route that will display the weather data
            return $this->redirectToRoute("app_weather_show");
        }

        // Render the form view if not a POST request
        return $this->render("weather/weather.html.twig");
    }

    #[Route("/weather/show", name: "app_weather_show")]
    public function showWeather(SessionInterface $session): Response
    {
        // Retrieve the weather data from the session
        $weatherData = $session->get("weatherData");
        $city = $session->get("city");

        // Render the weather data view
        return $this->render("weather/show.html.twig", [
            "weatherData" => $weatherData,
            "city" => $city,
        ]);
    }
}
