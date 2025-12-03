export interface CreateReview {
    driveUuid: string
    rate: number
    comment?: string
}

export interface DriverReview {
    uuid: string
    author: {
        uuid: string
        pseudo: string
    }
    rate: number
    comment?: string
    status: string
    createdAt: string
}

export interface PaginatedDriverReviews {
    data: DriverReview[]
    total: number
    totalPages: number
    currentPage: number
    perPage: number
    sortDir: "ASC" | "DESC"
    averageRate: number
}

export interface AuthorReview {
    uuid: string
    driver: {
        uuid: string
        pseudo: string
    }
    drive: {
        uuid: string
        reference: string
        depart: string
        departAt: string
        arrived: string
    }
    rate: number
    comment?: string
    status: string
    createdAt: string
}

export interface EmployeeReview {
    uuid: string
    driver: {
        uuid: string
        pseudo: string
        email: string
    }
    author: {
        uuid: string
        pseudo: string
        email: string
    }
    drive: {
        uuid: string
        reference: string
    }
    rate: number
    comment?: string
    status: string
    createdAt: string
}

export interface PublicReview {
    uuid: string
    author: {
        uuid: string
        pseudo: string
    }
    rate: number
    comment?: string
    createdAt: string
}