import { Endpoints } from "@api/endpoints"
import { getRequest, postRequest } from "@api/request"
import type { PaginatedResponse } from "@models/pagination"
import type { DriveDispute, ModerateDispute } from "@models/dispute"


export function getDriveDisputes(
    page: number = 1
): Promise<PaginatedResponse<DriveDispute>> {
    return getRequest<PaginatedResponse<DriveDispute>>(
        `${Endpoints.EMPLOYEE}/drives/dispute?page=${page}`
    )
}

export function moderateDriveDispute(
    driveUuid: string,
    participantUuid: string,
    payload: ModerateDispute
): Promise<{ message: string }> {
    return postRequest(
        `${Endpoints.EMPLOYEE}/drives/${driveUuid}/disputes/${participantUuid}/resolve`,
        payload
    )
}