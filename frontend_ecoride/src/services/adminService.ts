import { Endpoints } from "@api/endpoints"
import { getRequest, postRequest } from "@api/request"

import type { PaginatedResponse } from "@models/pagination"
import type { CreateEmployee, CreateEmployeeResponse, ReadUserResponse } from "@models/user"

export function getEmployees(
    page: number = 1
): Promise<PaginatedResponse<ReadUserResponse>> {
    return getRequest<PaginatedResponse<ReadUserResponse>>(
        `${Endpoints.ADMIN}/employee?page=${page}`
    )
}

export function createEmployee(
    payload: CreateEmployee
): Promise<CreateEmployeeResponse> {
    return postRequest<CreateEmployee, CreateEmployeeResponse>(
        `${Endpoints.ADMIN}/create-user`,
        payload
    )
}

export function banUser(
    userUuid: string
): Promise<{ message: string }> {
    return postRequest(
        `${Endpoints.ADMIN}/ban-user?userUuid=${userUuid}`,
        null
    )
}

export function unbanUser(
    userUuid: string
): Promise<{ message: string }> {
    return postRequest(
        `${Endpoints.ADMIN}/unban-user?userUuid=${userUuid}`,
        null
    )
}