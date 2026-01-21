export const Endpoints = {
    LOGIN: "/auth/login",
    LOGOUT: "/auth/logout",
    REGISTER: "auth/register",

    USER: "/user",
    EMPLOYEE: "/employee",
    ADMIN: "/admin",

    DRIVES: "/drives",
    SEARCH_DRIVE: "/drives/search",

    VEHICLE: "/vehicle",

    REVIEWS: "/reviews",

    PREFERENCES: "/prefs",

} as const

export type Endpoint = typeof Endpoints[keyof typeof Endpoints]