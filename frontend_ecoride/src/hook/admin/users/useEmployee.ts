import { useState, useEffect, useCallback } from "react"

import type { ReadUserResponse } from "@models/user"
import type { PaginatedResponse } from "@models/pagination"

import { getEmployees } from "@services/adminService"

export function useEmployee() {
    const [employees, setEmployees] = useState<ReadUserResponse[]>([])
    const [totalEmployees, setTotalEmployees] = useState<number>(0)
    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchEmployees = useCallback(async (newPage = 1) => {
        setLoading(true)
        setError(null)

        try {
            const response: PaginatedResponse<ReadUserResponse> = await getEmployees(newPage)
            setEmployees(response.data)
            setTotalEmployees(response.total)
            setTotalPages(response.totalPages)
            setPage(response.currentPage)
        } catch (error: any) {
            setError("Une erreur est survenue lors du chargement des employés.")
        } finally {
            setLoading(false)
        }
    }, [])

    useEffect(() => {
        fetchEmployees()
    }, [fetchEmployees])

    const changePage = useCallback(async (newPage: number) => {
        await fetchEmployees(newPage)
    }, [fetchEmployees])

    return {
        employees,
        totalEmployees,
        page,
        totalPages,
        loading,
        error,
        refresh: fetchEmployees,
        changePage,
        setError
    }
}