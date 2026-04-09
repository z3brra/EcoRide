import { postRequest } from "@api/request";
import { Endpoints } from "@api/endpoints";
import type { ContactPayload } from "@models/contact";

export function sendContactMessage(
    payload: ContactPayload
): Promise<{ message: string } | void> {
    return postRequest<ContactPayload, { message: string } | void>(
        `${Endpoints.CONTACT}`,
        payload
    )
}