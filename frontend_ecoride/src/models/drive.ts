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

// export interface DriveSearchResponse<T> {
//     data: T[]
//     total: number
//     totalPages: 1
// }