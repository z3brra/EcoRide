import { postRequest, putRequest } from "@api/request"
import { Endpoints } from "@api/endpoints"

import type {
    // RegisterUserPayload,
    UpdateUserPayload,
    UpdateUserResponse,
    CurrentUserResponse,
} from "@models/user"

export async function updateUser(
    payload: UpdateUserPayload
): Promise<UpdateUserResponse> {
    return putRequest<UpdateUserPayload, UpdateUserResponse>(
        Endpoints.USER,
        payload
    )
}

export async function becomeDriver(): Promise<CurrentUserResponse> {
    return postRequest<null, CurrentUserResponse>(
        `${Endpoints.USER}/driver`,
        null
    )
}