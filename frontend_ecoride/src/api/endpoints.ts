export const Endpoints = {
    LOGIN: "/auth/login",
    LOGOUT: "/auth/logout",
    USER: "/user",

    DRIVES: "/drives",
    SEARCH_DRIVE: "/drives/search",

    VEHICLE: "/vehicle",

    REVIEWS: "/reviews",

    PREFERENCES: "/prefs",

} as const

export type Endpoint = typeof Endpoints[keyof typeof Endpoints]