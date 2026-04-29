export interface Feature {
    icon: string;
    title: string;
    description: string;
    iconBgClass: string;
}

export interface Plan {
    name: string;
    price_monthly: number | null;
    price_annual: number | null;
    description: string;
    features: string[];
    is_recommended: boolean;
}

