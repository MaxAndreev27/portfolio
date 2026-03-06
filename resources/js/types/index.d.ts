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

export interface MenuSettings {
    hero_is_featured: boolean;
    about_is_featured: boolean;
    projects_is_featured: boolean;
    technology_is_featured: boolean;
    contact_is_featured: boolean;
}

export interface HeroSettings {
    hero_is_featured: boolean;
    hero_title: string;
    hero_description: string;
    hero_image: string | null;
    hero_button_about: string;
    hero_button_contact: string;
}

interface TimelineEvent {
    period: string;
    description: string;
}

interface AboutSkill {
    category: string;
    content: string;
}

export interface AboutSettings {
    about_is_featured: boolean;
    about_title: string;
    about_timeline: TimelineEvent[];
    about_skills: AboutSkill[];
}

export interface ProjectsSettings {
    projects_is_featured: boolean;
    projects_title: string;
}

export interface TechnologySettings {
    technology_is_featured: boolean;
}

export interface ContactSettings {
    contact_is_featured: boolean;
    contact_title: string;
}

interface SocialLinks {
    label: string;
    url: string;
    icon: string | null;
}

export interface FooterSettings {
    footer_social_links: SocialLinks[];
    footer_copyright: string;
    footer_powered: string;
}

export interface SeoSettings {
    seo_title: string;
    seo_description: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
