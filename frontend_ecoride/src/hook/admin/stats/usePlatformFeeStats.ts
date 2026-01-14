import { useState, useEffect, useCallback, useMemo } from "react"

import type { PlatformFeeRange, PlatformFeeStatsResponse } from "@models/adminStats"
import { getPlatformFeeStats } from "@services/adminStatsService"

export function usePlatformFeeStats() {
    const [range, setRange] = useState<PlatformFeeRange>("today")
    const [year, setYear] = useState<string>(String(new Date().getFullYear()))

    const [data, setData] = useState<PlatformFeeStatsResponse | null>(null)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const parsedYear = useMemo(() => {
        const n = Number(year)
        return Number.isFinite(n) ? n : undefined
    }, [year])

    const fetchStats = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const response = await getPlatformFeeStats(
                range,
                range === "year" ? parsedYear : undefined
            )
            setData(response)
        } catch (error: any) {
            setError("Impossible de charger les statistiques.")
            setData(null)
        } finally {
            setLoading(false)
        }
    }, [range, parsedYear])

    useEffect(() => {
        fetchStats()
    }, [fetchStats])

    return {
        range,
        setRange,
        year,
        setYear,

        data,
        loading,
        error,
        setError,

        refresh: fetchStats
    }
}