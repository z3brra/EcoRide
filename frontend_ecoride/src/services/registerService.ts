import { Endpoints } from "@api/endpoints"
import { postRequest } from "@api/request"
import type { RegisterUserPayload, CurrentUserResponse } from "@models/user"

export function registerUser(
    payload: RegisterUserPayload
): Promise<CurrentUserResponse> {
    return postRequest<RegisterUserPayload, CurrentUserResponse>(
        `${Endpoints.REGISTER}`,
        payload
    )
}