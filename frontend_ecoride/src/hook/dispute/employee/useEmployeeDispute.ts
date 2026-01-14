import { useState, useEffect, useCallback } from "react"
import type { DriveDispute } from "@models/dispute"
import type { PaginatedResponse } from "@models/pagination"

import { getDriveDisputes } from "@services/disputeService"

export function useEmployeeDispute() {
    const [disputes, setDisputes] = useState<DriveDispute[]>([])
    const [totalDisputes, setTotalDisputes] = useState<number>(0)
    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchEmployeeDisputes = useCallback(async (newPage = 1) => {
        setLoading(false)
        setError(null)

        try {
            const response: PaginatedResponse<DriveDispute> = await getDriveDisputes(newPage)
            setDisputes(response.data)
            setTotalDisputes(response.total)
            setTotalPages(response.totalPages)
            setPage(response.currentPage)
        } catch (error: any) {
            setError("Une erreur est survenue lors du chargement des commentaires.")
        } finally {
            setLoading(false)
        }
    }, [])

    useEffect(() => {
        fetchEmployeeDisputes()
    }, [fetchEmployeeDisputes])

    const changePage = useCallback(
        async (newPage: number) => {
            await fetchEmployeeDisputes(newPage)
        },
        [fetchEmployeeDisputes]
    )

    return {
        disputes,
        totalDisputes,
        page,
        totalPages,
        loading,
        error,
        refresh: fetchEmployeeDisputes,
        changePage,
        setError
    }
}