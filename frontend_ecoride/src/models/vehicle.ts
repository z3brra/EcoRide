export interface Vehicle {
    uuid: string
    licensePlate: string
    isElectric: boolean
    color: string
    seats: number
    createdAt: string
    updatedAt: string
    ownerUuid: string
    ownerPseudo: string
}

export interface CreateVehicle {
    licensePlate: string
    firstLicenseDate: string
    isElectric: boolean
    color: string
    seats: number
}

export interface UpdateVehicle {
    firstLicenseDate: string
    isElectric: boolean
    color: string
    seats: number
}