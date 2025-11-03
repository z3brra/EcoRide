export const PUBLIC_ROUTES = {
    HOME: "/" as const,

    // DRIVES: "/drives" as const,

    CONTACT: "/contact" as const,

    LOGIN: "/login" as const,
    REGISTER: "/register" as const,

    DRIVES: {
        TO: "/drives" as const,
        REL: "drives" as const,
        DETAIL_PATTERN: ":uuid" as const,
        DETAIL: (uuid: string) => `${PUBLIC_ROUTES.DRIVES.TO}/${uuid}` as const,
    }
}

export const USER_ROUTES = {
    USER: "/user" as const
}