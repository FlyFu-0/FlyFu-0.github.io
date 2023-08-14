import { weatherInfoByCity } from "./content.js";
import { getWeatherData } from "./weatherApi.js";

export const handelWeatherByGeolocation = async () => {
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    }

    const success = async (pos) => {
        const crd = pos.coords;

        const response = await fetch(
            `https://api.geoapify.com/v1/geocode/reverse?lat=${crd.latitude}&lon=${crd.longitude}&apiKey=7586a389f1ef436fabf1dff7eb20ea26`
        );

        const data = await response.json();
        console.log(data);

        const weather = await getWeatherData(data.features[0].properties.city);
        weatherInfoByCity(data.features[0].properties.city, weather);
        localStorage.setItem('city', JSON.stringify(data.features[0].properties.city));
    }

    const error = async (err) => {
        const city = JSON.parse(localStorage.getItem("city")) || 'Minsk';
        const weather = await getWeatherData(city);
        weatherInfoByCity(city, weather);
        console.log(err.code + ' ' + err.message);
    }

    navigator.geolocation.getCurrentPosition(success, error, options);
}