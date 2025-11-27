import { Endpoints } from "@api/endpoints"
import { postRequest } from "@api/request"
import type { CreateReview } from "@models/review"

export function createReview(
    payload: CreateReview
): Promise<{ message: string}> {
    return postRequest<CreateReview, { message: string }>(
        `${Endpoints.REVIEWS}`,
        payload
    )
}