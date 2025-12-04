import { Endpoints } from "@api/endpoints"
import { getRequest, postRequest } from "@api/request"
import type { PaginatedResponse } from "@models/pagination"
import type {
    CreateReview,
    EmployeeReview,
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

export function getEmployeeReviews(
    page: number = 1
): Promise<PaginatedResponse<EmployeeReview>> {
    return getRequest<PaginatedResponse<EmployeeReview>>(
        `${Endpoints.EMPLOYEE}/reviews?page=${page}`
    )
}

export function moderateEmployeeReview(
    uuid: string,
    action: "validate" | "refuse"
): Promise<{ message: string }> {
    return postRequest<{ action: string }, {message: string }>(
        `${Endpoints.EMPLOYEE}/reviews/${uuid}`,
        { action }
    )
}