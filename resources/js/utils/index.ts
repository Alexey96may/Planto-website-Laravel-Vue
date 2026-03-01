export const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    if (target) {
        target.src = '/images/placeholder.png';
        target.onerror = null;
    }
};
