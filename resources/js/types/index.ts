export interface Category {
    id: number;
    title: string;
    slug: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
    page: number | null;
}

export interface PaginatedResponse<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface ProductFilters {
    search: string | null;
    min_price: number | string | null;
    max_price: number | string | null;
    sort: 'popular' | 'newest' | 'price_asc' | 'price_desc' | string;
    in_stock: 'true' | 'false' | boolean | null;
}

export type SortOption = 'popular' | 'newest' | 'price_asc' | 'price_desc';

export interface Feature {
    id: number;
    title: string;
    description: string;
    image: string;
    image_url: string;
    link: string;
    order: number;
    is_active: number | boolean;
    created_at: string;
    updated_at: string;
}

export type Role = 'admin' | 'user';

export interface Comment {
    id: number;
    user_id: number;
    body: string;
    rating: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface Review extends Comment {
    user: User;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role: Role;
    avatar: string | null;
    avatar_url: string | null;
    created_at: string;
    updated_at: string;
}

export interface AuthProps {
    user: User;
}

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

export interface UserForm {
    name: string;
    email: string;
    avatar: File | null;
    _method: string;
}

export interface CommentForm {
    body: string;
    rating: number;
}
