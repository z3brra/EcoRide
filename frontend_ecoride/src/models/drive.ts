export interface Drive {
    uuid: string
    reference: string
    status: string
    owner: {
        uuid: string
        pseudo: string
    }
    vehicle: {
        uuid: string
        isElectric: boolean
        color: string
        seats: number
    }
    participantsCount: number
    availableSeats: number
    price: number
    distance: number
    depart: string
    departAt: string
    arrived: string
    arrivedAt: string
    createdAt: string
    updatedAt?: string
}

export interface DriveSeach {
    depart: string
    arrived: string
    departAt: string
}

export interface DriveJoinedFilters {
    status?: "all" | "open" | "in_progress" | "finished" | "cancelled"
    when?: "all" | "upcoming" | "past"
    includeCancelled?: boolean
    sortDir?: "asc" | "desc"
    page?: number
}

export interface DriveJoinedPayload {
    status?: "open" | "in_progress" | "finished" | "cancelled"
    when?: "upcoming" | "past"
    includeCancelled?: boolean
    sortDir?: "asc" | "desc"
    page?: number
}

export interface DriverOwnedFilters {
    status?: "all" | "open" | "in_progress" | "finished" | "cancelled"
    depart?: string
    arrived?: string
    includeCancelled?: boolean
    sortDir?: "asc" | "desc"
    page?: number
}

export interface DriverOwnedPayload {
    status?: "open" | "in_progress" | "finished" | "cancelled"
    depart?: string
    arrived?: string
    includeCancelled?: boolean
    sortDir?: "asc" | "desc"
    page?: number
}

export interface CreateDrivePayload {
    vehicleUuid: string
    price: number
    distance: number
    depart: string
    departAt: string
    arrived: string
    arrivedAt: string
}

export interface UpdateDrivePayload {
    departAt?: string
    arrivedAt?: string
}
// export interface DriveSearchResponse<T> {
//     data: T[]
//     total: number
//     totalPages: 1
// }