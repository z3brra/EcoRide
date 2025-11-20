export interface PaginatedResponse<T> {
    data: T[]
    total: number
    totalPages: number
    currentPage: number
    perPage: number
    sortBy?: string
    sortDir?: string
    when?: string
}