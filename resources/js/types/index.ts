export interface Category {
    id: number;
    title: string;
    slug: string;
    created_at: string;
    updated_at: string;
}

export interface Comment {}

export interface Feature {}

export interface Product {
    id: number;
    title: string;
    description: string;
    price: string;
    image: string;
    image_url: string;
    category_id: number;
    sales_count: number;
    is_trending: boolean;
    trending_order: number;
    stock: number;
    created_at: string;
    updated_at: string;
    category?: Category;
}

export type CartItems = Record<number, number>;

export interface CartItem extends Product {
    quantity: number;
}
