import { getRequest } from "@api/request"
import { Endpoints } from "@api/endpoints"
import type { Vehicle } from "@models/vehicle"
import type { PaginatedResponse } from "@models/pagination"

export async function getVehicles(
    page: number
): Promise<PaginatedResponse<Vehicle>> {
    return getRequest<PaginatedResponse<Vehicle>>(
        `${Endpoints.VEHICLE}?page=${page}`
    )
}