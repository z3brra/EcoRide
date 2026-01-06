export interface DriveDispute {
    drive: {
        uuid: string
        reference: string
        status: string
        owner: {
            uuid: string
            pseudo: string
            email: string
            roles: string[]
            credits: number
            isBanned: boolean
        }
        vehicle: {
            uuid: string
            isElectric: boolean
            color: string
            seats: number
        }
        availableSeats: number
        price: number
        depart: string
        departAt: string
        arrived: string
        arrivedAt: string
    }
    participant: {
        uuid: string
        pseudo: string
        email: string
        roles: string[]
    }
    amount: number
    fee: number
    occurredAt: string
    status: string
    comment: string | null
}


export interface ModerateDispute {
    action: "confirm" | "refund"
    comment?: string
}