import { useState, useEffect, useCallback } from "react"
import type { Vehicle } from "@models/vehicle"
import type { PaginatedResponse } from "@models/pagination"

import { getVehicles } from "@services/vehicleService"

export function useVehicles(options: { enabled?: boolean} = {}) {
    const {enabled  = true } = options
    const [data, setData] = useState<Vehicle[]>([])
    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchVehicles = useCallback(async (newPage = 1) => {
        if (!enabled) {
            return
        }
        setLoading(true)
        setError(null)

        try {
            const response: PaginatedResponse<Vehicle> = await getVehicles(newPage)
            setData(response.data)
            setTotalPages(response.totalPages)
            setPage(response.currentPage)
        } catch (error: any) {
            setError("Une erreur est survenur lors du chargement des véhicules.")
        } finally {
            setLoading(false)
        }
    }, [])

    useEffect(() => {
        fetchVehicles()
    }, [fetchVehicles])

    const changePage = useCallback(
        async (newPage: number) => {
            await fetchVehicles(newPage)
        },
        [fetchVehicles]
    )
    // (newPage: number) => setPage(newPage)

    return {
        data,
        page,
        totalPages,
        loading,
        error,
        setError,
        changePage,
        refresh: fetchVehicles
    }
}