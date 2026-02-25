import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    flash: {
        success: string;
        error: string;
    };
    features: {
        registration: boolean;
        twoFactorAuthentication: boolean;
    };
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Project {
    id: number;
    title: string;
    slug: string;
    description: string;
    excerpt: string;
    image: string | null;
    url: string | null;
    github_url: string | null;
    completed_at: string | null;
    is_featured: boolean;
    order: number;
    status: 'draft' | 'published' | 'archived';
    tags: string[];
}

export type BreadcrumbItemType = BreadcrumbItem;
