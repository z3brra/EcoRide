import { useState, useCallback } from "react"

import type { Drive, DriveSeach } from "@models/drive"
import type { PaginatedResponse } from "@models/pagination"

import { searchDrives } from "@services/driveService"

export function useDriveSearch() {
    const [data, setData] = useState<Drive[]>([])
    
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)

    const [hasSearched, setHasSearched] = useState<boolean>(false)

    const [criteria, setCriteria] = useState<DriveSeach | null>(null)

    const search = useCallback(
        async (payload: DriveSeach, newPage = 1) => {
            setLoading(true)
            setError(null)
            setHasSearched(true)
            try {
                const response: PaginatedResponse<Drive> = await searchDrives(payload, newPage)
                setData(response.data)
                setTotalPages(response.totalPages)
                setPage(response.currentPage)
                setCriteria(payload)
            } catch (error: any) {
                setError(error.message ?? "Une erreur est survenue lors de la recherche.")
                setData([])
            } finally {
                setLoading(false)
            }
        },
        []
    )

    const changePage = useCallback(
        async (newPage: number) => {
            if (!criteria) return
            await search(criteria, newPage)
        },
        [criteria, search]
    )

    return {
        data,
        page,
        totalPages,
        hasSearched,
        search,
        changePage,

        loading,
        setLoading,
        error,
        setError,
    }

}