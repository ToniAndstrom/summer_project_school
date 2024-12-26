<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountryController extends AbstractController
{
    #[Route('/', name: 'app_country')]
    public function index(): Response
    { 
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://restcountries.com/v3.1/all');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $data = json_decode($result, true);
        return $this->render('country/index.html.twig', [
            'result' => $result, 'data' => $data
        ]);
    }

    #[Route("/weather", name: "app_weather")]
    public function weather(): Response
    {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $url = 'https://api.weatherapi.com/v1/current.json';

            $accessToken = "a7b756eaee0a4fde87e93444242205";

            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_URL);
            $data2 = [
                "location" => "name",
                "city" => $city
            ];

            $ch2 = curl_init($url);

            curl_setopt($ch2, CURLOPT_URL, $url);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch2, CURLOPT_POST, 1);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data2));

            $headers = array();

            $headers[] = 'accept: application/json';
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer' .  $accessToken;
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);

            $result2 = curl_exec($ch2);
            if (curl_errno($ch2)) {
                echo 'Error:' . curl_error($ch2);
            }
            curl_close($ch2);

            $data3 = json_decode($result2, true);


            return $this->render("weather/weather.html.twig", [
                "data2" => $data2, "data3" => $data3, "city" => $city
            ]);
        }
    }
};
