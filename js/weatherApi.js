export const getWeatherData = async (city) => {
    try {
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=eb926e4812a74a7e90c2c65b5860f709&units=metric`
        );

        return await response.json();
    } catch (error) {
        console.error(error);
    }

}