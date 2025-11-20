import { useState, useCallback } from "react"
import { getOwnedDrives } from "@services/driveService"
import type { Drive, DriverOwnedFilters } from "@models/drive"
import type { PaginatedResponse } from "@models/pagination"

export function useOwnedDrives() {
    const [filters, setFilters] = useState<DriverOwnedFilters>({
        status: "all",
        depart: "",
        arrived: "",
        includeCancelled: false,
        sortDir: "asc",
        page: 1,
    })

    const [data, setData] = useState<Drive[]>([])
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchDrives = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const response: PaginatedResponse<Drive> = await getOwnedDrives(filters)
            setData(response.data ?? [])
            setTotalPages(response.totalPages)
        } catch (error: any) {
            setError("Une erreur est survenue lors de la récupération des trajets.")
        } finally {
            setLoading(false)
        }
    }, [filters])

    // useEffect(() => {
    //     fetchDrives()
    // }, [fetchDrives])

    const search = () => {
        setFilters((prev) => ({ ...prev, page: 1 }))
        fetchDrives()
    }

    const updateFilters = (newFilters: Omit<DriverOwnedFilters, "page">) => {
        setFilters({
            ...filters,
            ...newFilters,
            page: 1,
        })
    }

    const changePage = (page: number) => {
        setFilters({
            ...filters,
            page
        })
    }

    return {
        data,
        loading,
        error,
        filters,
        totalPages,
        changePage,
        updateFilters,
        search,
        refresh :fetchDrives,
        setError
    }
}