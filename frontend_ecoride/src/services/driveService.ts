import type  { PaginatedResponse } from "@components/common/Pagination/Pagination"
import type { Drive, DriveSeach } from "@models/drive"

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