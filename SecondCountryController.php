<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route("/weather", name: "app_weather")]
    public function weather(Request $request): Response
    {
        if ($request->isMethod("POST")) {
            $city = $request->request->get("city" , "default_city");

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
            if (!empty($_POST["city"])) {
                echo "Yes, city is set";    
            } else {  
                echo "No, city is not set";
            }

            return $this->render("weather/weather.html.twig", [
                "weatherData" => $weatherData,
                "city" => $city,
            ]);
        }

        // Render a default view if not POST request
        return $this->render("weather/weather.html.twig");
   

   /* if (isset($_POST["city"])){
        if(empty($_POST["city"])){
            echo "enter city name";
        }else{
            $city = $_POST["city"];
            $api_key = "a7b756eaee0a4fde87e93444242205";
            $api = "https://api.weatherapi.com/v1/current.json?q=$city&key=$api_key";
            $api_data = file_get_contents($api);
            print_r($api_data) ;

            $weather =json_decode($api_data,true);
            print_r($weather);

            echo $weather ["current"]["temp_c"]
        }
        return $this->render("weather/weather.html.twig", [
            "api_data" => $api_data,
            "city" => $city,
        ]);
    }


}*/
}
}