import { Endpoints } from "@api/endpoints"
import { getRequest } from "@api/request"

import type { PlatformFeeRange, PlatformFeeStatsResponse } from "@models/adminStats"

export function getPlatformFeeStats(
    range: PlatformFeeRange,
    year?: number
): Promise<PlatformFeeStatsResponse> {
    const params = new URLSearchParams({ range })

    if (range === "year") {
        const safeYear = year ?? new Date().getFullYear()
        params.set("year", String(safeYear))
    }

    return getRequest<PlatformFeeStatsResponse>(
        `${Endpoints.ADMIN}/stats/platform-fee?${params.toString()}`
    )
}