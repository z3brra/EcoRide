import { useState, useEffect, useCallback } from "react"
import { getOwnedDrives } from "@services/driveService"
import type { Drive, DriverOwnedFilters } from "@models/drive"
import type { PaginatedResponse } from "@models/pagination"

const DEFAULT_FILTERS: DriverOwnedFilters = {
    status: "all",
    depart: "",
    arrived: "",
    includeCancelled: false,
    sortDir: "asc",
    page: 1,
}

export function useOwnedDrives() {
    const [draftFilters, setDraftFilters] = useState<DriverOwnedFilters>(DEFAULT_FILTERS)

    const [appliedFilters, setAppliedFilters] = useState<DriverOwnedFilters>(DEFAULT_FILTERS)

    // const [filters, setFilters] = useState<DriverOwnedFilters>({
    //     status: "all",
    //     depart: "",
    //     arrived: "",
    //     includeCancelled: false,
    //     sortDir: "asc",
    //     page: 1,
    // })

    const [data, setData] = useState<Drive[]>([])
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchOwnedDrives = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const response: PaginatedResponse<Drive> = await getOwnedDrives(appliedFilters)
            setData(response.data ?? [])
            setTotalPages(response.totalPages)
        } catch (error: any) {
            setError("Une erreur est survenue lors de la récupération des trajets.")
        } finally {
            setLoading(false)
        }
    }, [appliedFilters])

    useEffect(() => {
        fetchOwnedDrives()
    }, [fetchOwnedDrives])

    const updateFilters = (newFilters: Omit<DriverOwnedFilters, "page">) => {
        setDraftFilters((prev) => ({
            ...prev,
            ...newFilters,
        }))
    }

    const search = () => {
        setAppliedFilters({
            ...draftFilters,
            page: 1
        })
    }

    const changePage = (page: number) => {
        setAppliedFilters((prev) => ({
            ...prev,
            page
        }))

        setDraftFilters((prev) => ({
            ...prev,
            page
        }))
    }

    return {
        data,
        loading,
        error,
        filters: draftFilters,
        totalPages,
        changePage,
        updateFilters,
        search,
        refresh: fetchOwnedDrives,
        setError
    }
}