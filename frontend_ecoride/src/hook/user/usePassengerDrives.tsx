import { useState, useEffect, useCallback } from "react"
import { getJoinedDrives } from "@services/driveService"
import type {
    Drive,
    DriveJoinedFilters
} from "@models/drive"

import type { PaginatedResponse } from "@models/pagination"

export function usePassengerDrives() {
    const [data, setData] = useState<Drive[]>([])
    const [filters, setFilters] = useState<DriveJoinedFilters>({
        status: "all",
        when: "all",
        includeCancelled: false,
        sortDir: "asc",
        page: 1
    })
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchJoinedDrives = useCallback(async () => {
        setLoading(true)
        setError(null)
        try {
            const response: PaginatedResponse<Drive> = await getJoinedDrives(filters)
            setData(response.data)
            setTotalPages(response.totalPages)
        } catch (error: any) {
            setError("Une erreur est survenur lors du chargement des trajets.")
        } finally {
            setLoading(false)
        }
    }, [filters])

    useEffect(() => {
        fetchJoinedDrives()
    }, [fetchJoinedDrives])

    const changePage = (page: number) => {
        setFilters(prev => ({ ...prev, page }))
    }

    const updateFilters = (partial: Partial<DriveJoinedFilters>) => {
        setFilters(prev => ({
            ...prev,
            ...partial,
            page: 1
        }))
    }

    return {
        data,
        filters,
        totalPages,
        loading,
        error,
        setError,
        changePage,
        updateFilters,
    }
}