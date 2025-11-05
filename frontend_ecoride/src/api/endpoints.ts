export const Endpoints = {
    LOGIN: "/auth/login",
    LOGOUT: "/auth/logout",
    USER: "/user",

    DRIVES: "/drives",
    SEARCH_DRIVE: "/drives/search",
} as const

export type Endpoint = typeof Endpoints[keyof typeof Endpoints]