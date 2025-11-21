import { deleteRequest, getRequest, postRequest, putRequest } from "@api/request"
import { Endpoints } from "@api/endpoints"
import type { CreateVehicle, UpdateVehicle, Vehicle, AllVehiculeResponse} from "@models/vehicle"
import type { PaginatedResponse } from "@models/pagination"

export async function getVehicles(
    page: number
): Promise<PaginatedResponse<Vehicle>> {
    return getRequest<PaginatedResponse<Vehicle>>(
        `${Endpoints.VEHICLE}?page=${page}`
    )
}

export function createVehicle(
    paylod: CreateVehicle
): Promise<Vehicle> {
    return postRequest<CreateVehicle, Vehicle>(
        `${Endpoints.VEHICLE}`,
        paylod
    )
}

export function updateVehicle(
    uuid: string,
    payload: UpdateVehicle
): Promise<Vehicle> {
    return putRequest<UpdateVehicle, Vehicle>(
        `${Endpoints.VEHICLE}/${uuid}`,
        payload
    )
}

export async function deleteVehicle(
    uuid: string
): Promise<void> {
    return deleteRequest<void>(
        `${Endpoints.VEHICLE}/${uuid}`
    )
}

export async function fetchAllVehicles(): Promise<AllVehiculeResponse> {
    return getRequest<AllVehiculeResponse>(
        `${Endpoints.VEHICLE}/all`
    )
}