export const Endpoints = {
    LOGIN: "/auth/login",
    LOGOUT: "/auth/logout",
    ME: "/user",
} as const

export type Endpoint = typeof Endpoints[keyof typeof Endpoints]