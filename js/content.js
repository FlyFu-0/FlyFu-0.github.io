import { cTof, capitalizeFirstLetter, fToc, windDirection } from "./helper.js";
import { findLocation } from "./searchLocation.js";

export const weatherInfoByCity = (city, weatherData) => {
    const cityName = document.querySelector(".city-name");
    const windInfo = document.querySelector(".info-of-wind");
    const pressureInfo = document.querySelector(".info-of-pressure");
    const humidityInfo = document.querySelector(".info-of-humidity");
    const cloudsInfo = document.querySelector(".info-of-clouds");
    const weatherDescription = document.querySelector(".weather-description");
    const weatherIcon = document.querySelector(".weather-inner > img");
    const temperature = document.querySelector(".weather-temp");

    const units = document.querySelector(".temp-units");
    const unitC = document.querySelector(".units-c");
    const unitF = document.querySelector(".units-f");

    const findBtn = document.querySelector(".city-location");

    cityName.innerHTML = weatherData.name;
    temperature.textContent = `${Math.floor(weatherData.main.temp)}`;
    units.innerHTML ='&deg;C';
    unitC.classList.add("unit-current");
    unitF.classList.remove("unit-current");
    windInfo.innerHTML = `${weatherData.wind.speed} mps, ${windDirection(weatherData.wind.deg)}`;
    pressureInfo.innerHTML = `${weatherData.main.pressure} mm Hg`;
    humidityInfo.innerHTML = `${weatherData.main.humidity}%`;
    cloudsInfo.innerHTML = `${weatherData.clouds.all}%`;
    weatherDescription.innerHTML = capitalizeFirstLetter(weatherData.weather[0].description);
    weatherIcon.src = `https://openweathermap.org/img/w/${weatherData.weather[0].icon}.png`;

    console.log(weatherData);

    unitC.addEventListener("click", () => {
        if(unitC.classList.contains("unit-current"))
            return;

        unitC.classList.add("unit-current");
        unitF.classList.remove("unit-current");
        const weatherTemp = document.querySelector(".weather-temp");
        weatherTemp.textContent = Math.round(fToc(weatherTemp.textContent));
        units.innerHTML = '&deg;C';
    })

    unitF.addEventListener("click", () => {
        if(unitF.classList.contains("unit-current"))
            return;

        unitF.classList.add("unit-current");
        unitC.classList.remove("unit-current");
        const weatherTemp = document.querySelector(".weather-temp");
        weatherTemp.textContent = Math.round(cTof(weatherTemp.textContent));
        units.innerHTML = '&deg;F';
    })

    findBtn.addEventListener("click", () => {
        findLocation();
    });
}