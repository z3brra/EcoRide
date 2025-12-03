import { Endpoints } from "@api/endpoints"
import { getRequest, postRequest, putRequest } from "@api/request"
import type { CreateCustomPref, AggregatedPref } from "@models/driverPreference"

export async function fetchAllPref(): Promise<AggregatedPref> {
    return getRequest<AggregatedPref>(
        `${Endpoints.PREFERENCES}`
    )
}

export async function updateFixedPreferences(
    animals: boolean,
    smoke: boolean
): Promise<AggregatedPref> {
    return putRequest(
        `${Endpoints.PREFERENCES}`,
        {
            fixedPref: {animals, smoke }
        }
    )
}

export async function createCustomPreference(
    label: string
): Promise<AggregatedPref> {
    return postRequest<CreateCustomPref, AggregatedPref>(
        `${Endpoints.PREFERENCES}`,
        { label }
    )
}

export async function deleteCustomPreferences(
    uuids: string[]
): Promise<void> {
    const body = uuids.map((id) => ({ uuid: id }))
    return postRequest(
        `${Endpoints.PREFERENCES}/delete`,
        body
    )
}