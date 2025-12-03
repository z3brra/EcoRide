import { Endpoints } from "@api/endpoints"
import { getRequest, postRequest } from "@api/request"
import type {
    CreateReview,
    PaginatedDriverReviews
} from "@models/review"

export function createReview(
    payload: CreateReview
): Promise<{ message: string}> {
    return postRequest<CreateReview, { message: string }>(
        `${Endpoints.REVIEWS}`,
        payload
    )
}

export function fetchDriverReviews(
    page: number = 1
): Promise<PaginatedDriverReviews> {
    return getRequest<PaginatedDriverReviews>(
        `${Endpoints.USER}/reviews/driver?page=${page}&limit=5`
    )
}