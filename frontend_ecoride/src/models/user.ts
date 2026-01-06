export interface CurrentUserResponse {
    uuid: string
    pseudo: string
    email: string
    roles: string[]
    credits: number
    isBanned: boolean
    createdAt: Date
    updatedAt: Date | null
}

export interface LoginResponse {
    uuid: string
    email: string
    roles: string[]
}

export interface LogoutResponse {
    message: string
}


export interface RegisterUserPayload {
    pseudo: string
    email: string
    password: string
}

export interface RegisterUserResponse {
    uuid: string
    pseudo: string
    email: string
    roles: string[]
    credits: number
    isBanned: boolean
    createdAt: string
    updatedAt?: string
}

export interface UpdateUserPayload {
    pseudo?: string
    oldPassword?: string
    newPassword?: string
}

export interface UpdateUserResponse {
    message: string
}
