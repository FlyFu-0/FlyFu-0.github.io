import { weatherInfoByCity } from "./content.js";
import { handelWeatherByGeolocation } from "./geolocationApi.js";
import { getWeatherData } from "./weatherApi.js";

const setMyLocation = document.querySelector(".my-location");

const app = async () => {
    const city = JSON.parse(localStorage.getItem('city')) || 'Minsk';
    const weather = await getWeatherData(city);
    weatherInfoByCity(city, weather);
}

setMyLocation.addEventListener("click", () => {
    handelWeatherByGeolocation();
});

app();