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

export interface OrderItem {
    id: number;
    order_id: number;
    product_id: number;
    product_name: string;
    price: number;
    quantity: number;
    created_at: string;
    updated_at: string;
}

export type OrderStatus = 'new' | 'processing' | 'completed' | 'cancelled';

export interface Order {
    id: number;
    user_id: number;
    total_price: number;
    status: OrderStatus;
    customer_name: string;
    customer_phone: string;
    customer_email: string;
    delivery_address: string;
    comment: string | null;
    created_at: string;
    updated_at: string;
    items: OrderItem[];
}

export interface Settings {
    site_name: string;
    contact_phone: string;
    contact_email: string;
    contact_address: string;
    footer_rights: string;
    footer_main_text: string;

    link_fb: string | null;
    link_x: string | null;
    link_li: string | null;

    is_open: string;
    products_per_page: string;

    slider_main_limit: string;
    slider_features_limit: string;
    top_plants_limit: string;
    comments_limit: string;
    features_limit: string;
    reviews_limit: string;
    trendy_limit: string;
    hero_plants_limit: string;

    section_trendy_title: string;
    section_selling_title: string;
    section_reviews_title: string;
    section_slider_title: string;
    section_o2_title: string;
    section_hero_title: string;
    hero_main_text: string;

    section_trendy_link: string | null;
    section_selling_link: string | null;
    section_reviews_link: string | null;
    section_o2_link: string | null;
    live_demo_url: string;
}

export interface NavigationItem {
    id: number;
    title: string;
    link: string;
    location: 'header' | 'footer';
    order: number;
    parent_id: number | null;
    is_active: number; // 1 or 0
    type: 'link' | 'category';
    category_id: number | null;
    created_at: string;
    updated_at: string;
    children: NavigationItem[];
    category: Category | null;
}

export interface Navigation {
    header: NavigationItem[];
    footer: NavigationItem[];
}
