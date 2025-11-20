// import type  { PaginatedResponse } from "@components/common/Pagination/Pagination"
import type { 
    Drive,
    DriveSeach,
    DriveJoinedFilters,
    DriveJoinedPayload,
    DriverOwnedFilters,
    DriverOwnedPayload
} from "@models/drive"

import type { PaginatedResponse } from "@models/pagination"

import { getRequest, postRequest } from "@api/request"
import { Endpoints } from "@api/endpoints"

export async function searchDrives(
    payload: DriveSeach,
    page: number
): Promise<PaginatedResponse<Drive>> {
    return postRequest<DriveSeach, PaginatedResponse<Drive>>(
        `${Endpoints.SEARCH_DRIVE}?page=${page}`,
        payload
    )
}

export async function fetchOneDrive(
    uuid: string
): Promise<Drive> {
    return getRequest<Drive>(
        `${Endpoints.DRIVES}/${uuid}`
    )
}

export async function joinDrive(
    uuid: string
): Promise<{ message: string }> {
    return postRequest<null, { message: string }>(
        `${Endpoints.DRIVES}/${uuid}/join`,
        null
    )
}

export async function leaveDrive(
    uuid: string
): Promise<{ message: string }> {
    return postRequest<null, { message: string }>(
        `${Endpoints.DRIVES}/${uuid}/leave`,
        null
    )
}


function buildJoinedPayload(filters: DriveJoinedFilters): DriveJoinedPayload {
    const payload: DriveJoinedPayload = {}

    if (filters.status && filters.status !== "all") {
        payload.status = filters.status
    }

    if (filters.when && filters.when !== "all") {
        payload.when = filters.when
    }

    if (filters.includeCancelled) {
        payload.includeCancelled = true
    }

    if (filters.sortDir && filters.sortDir !== "asc") {
        payload.sortDir = filters.sortDir
    }

    return payload
}


export async function getJoinedDrives(
    filters: DriveJoinedFilters = {}
): Promise<PaginatedResponse<Drive>> {
    const payload = buildJoinedPayload(filters)
    const page = filters.page ?? 1

    return postRequest<DriveJoinedPayload, PaginatedResponse<Drive>>(
        `${Endpoints.USER}/drives/joined?page=${page}`,
        payload
    )
}

function buildOwnedPayload(filters: DriverOwnedFilters): DriverOwnedPayload {
    const payload: DriverOwnedPayload = {}

    if (filters.status && filters.status !== "all") {
        payload.status = filters.status
    }

    if (filters.depart) {
        payload.depart = filters.depart
    }

    if (filters.arrived) {
        payload.arrived = filters.arrived
    }

    if (filters.includeCancelled) {
        payload.includeCancelled = true
    }

    if (filters.sortDir && filters.sortDir !== "asc") {
        payload.sortDir = filters.sortDir
    }

    return payload
}

export async function getOwnedDrives(
    filters: DriverOwnedFilters = {}
): Promise<PaginatedResponse<Drive>> {
    const payload = buildOwnedPayload(filters)
    const page = filters.page ?? 1
    return postRequest<DriverOwnedPayload, PaginatedResponse<Drive>>(
        `${Endpoints.USER}/drives/owned?page=${page}`,
        payload
    )
}