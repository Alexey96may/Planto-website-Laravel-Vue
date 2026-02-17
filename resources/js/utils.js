export const handleImageError = (event) => {
    const placeholder = "/images/no-image.jpg";

    if (event.target.src !== window.location.origin + placeholder) {
        event.target.src = placeholder;
    }
};
