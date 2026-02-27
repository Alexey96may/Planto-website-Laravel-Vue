/**
 * Format the number in US dollars
 */
export const formatUSD = (amount: number | string): string => {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 2,
    }).format(+amount);
};

/**
 * Calculate the total cost
 */
export const calculateTotal = (
    price: number | string,
    quantity: number,
): number => {
    return +price * quantity;
};
