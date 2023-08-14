import { weatherInfoByCity } from "./content.js";
import { capitalizeFirstLetter } from "./helper.js";
import { getWeatherData } from "./weatherApi.js";


export const findLocation = async () => {
    const cityLocation = document.querySelector(".city-location");
    const cityOptions = document.querySelector(".city-options");
    const find = document.querySelector(".find");
    const searchLocation = document.querySelector(".find-location");
    const findBtn = document.querySelector(".find-btn");

    const findBlock = document.querySelector(".find-block");

    cityOptions.style.display = "none";
    find.style.display = "flex";

    const errorMessage = document.createElement("div");

    const showError = () => {
        errorMessage.classList.add("error-message");
        errorMessage.textContent = `City not found!`;
        findBlock.appendChild(errorMessage);

        setTimeout(() => {
            errorMessage.remove();
        }, 2500);
    }

    async function setFindingCity() {
        if(!searchLocation.value){
            console.error("Find input cannot be empty");
            searchLocation.focus();
            return;
        }

        try {
            const city = capitalizeFirstLetter(searchLocation.value);
            searchLocation.value = '';
            const weather = await getWeatherData(city);

            if(weather.message) {
                showError(weather.message);
                return;
            }

            weatherInfoByCity(city, weather);

            cityOptions.style.display = "block";
            find.style.display = "none";
            errorMessage.remove();
        } catch (error) {
            console.error(error);
        }
    }

    findBtn.addEventListener("click", () => {
        setFindingCity();
    });

    window.addEventListener("keydown", (e) => {
        if(e.key == "Enter")
            setFindingCity();;
    });

    window.addEventListener("click", (e) => {
        if(e.target == searchLocation || e.target == find || e.target == cityLocation)
            return;
        else {
            cityOptions.style.display = "block";
            find.style.display = "none";
            searchLocation.value = '';
            errorMessage.remove();
        }
    })
}