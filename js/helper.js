export const windDirection = (degree) => {
    const directions = ['↑ N', '↗ NE', '→ E', '↘ SE', '↓ S', '↙ SW', '← W', '↖ NW'];
    return directions[Math.round(degree / 45) % 8];
}

export const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

export const cTof = (celsius) => {
    return celsius * 1.8 + 32;
}

export const fToc = (fahrenheit) => {
    return (fahrenheit - 32) / 1.8;
}