import { useState, useEffect, useCallback } from "react"
import type { EmployeeReview } from "@models/review"
import type { PaginatedResponse } from "@models/pagination"

import { getEmployeeReviews } from "@services/reviewService"

export function useEmployeeReviews() {
    const [reviews, setReviews] = useState<EmployeeReview[]>([])
    const [totalReviews, setTotalReviews] = useState<number>(0)
    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const fetchEmployeeReviews = useCallback(async (newPage = 1) => {
        setLoading(true)
        setError(null)

        try {
            const response: PaginatedResponse<EmployeeReview> = await getEmployeeReviews(newPage)
            setReviews(response.data)
            setTotalReviews(response.total)
            setTotalPages(response.totalPages)
            setPage(response.currentPage)
        } catch (error: any) {
            setError("Une erreur est survenue lors du chargement des commentaires.")
        } finally {
            setLoading(false)
        }
    }, [])

    useEffect(() => {
        fetchEmployeeReviews()
    }, [fetchEmployeeReviews])

    const changePage = useCallback(
        async (newPage: number) => {
            await fetchEmployeeReviews(newPage)
        },
        [fetchEmployeeReviews]
    )

    return {
        reviews,
        totalReviews,
        page,
        totalPages,
        loading,
        error,
        refresh: fetchEmployeeReviews,
        changePage,
        setError,
    }
}