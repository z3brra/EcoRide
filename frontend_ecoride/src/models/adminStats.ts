export type PlatformFeeRange =
    | "year"
    | "this_year"
    | "last_3_month"
    | "this_month"
    | "last_7_days"
    | "today"

export type PlatformFeeGranularity =
    | "month"
    | "day"
    | "hour"
    | "half_day"
    | "half_hour"

export interface PlatformFeePoint {
    timestamp: string
    sum: number
}
export interface PlatformFeeSeries {
    key: string
    granularity: PlatformFeeGranularity
    points: PlatformFeePoint[]
}

export interface PlatformFeeStatsResponse {
    range: PlatformFeeRange
    from: string
    to: string
    timezone: string
    series: PlatformFeeSeries[]
    total: number
}

