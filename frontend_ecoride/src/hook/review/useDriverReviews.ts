import { useState, useEffect, useCallback } from "react"
import { fetchDriverReviews } from "@services/reviewService"
import type { DriverReview } from "@models/review"

export function useDriverReviews() {
    const [reviews, setReviews] = useState<DriverReview[]>([])
    const [totalReviews, setTotalReviews] = useState<number>(0)
    const [averageRate, setAverageRate] = useState<number>(0)

    const [page, setPage] = useState<number>(1)
    const [totalPages, setTotalPages] = useState<number>(1)

    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const load = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const response = await fetchDriverReviews(page)

            setReviews(response.data ?? [])
            setTotalReviews(response.total ?? 0)
            setAverageRate(response.averageRate ?? 0)
            setTotalPages(response.totalPages ?? 1)
        } catch (error: any) {
            setError("Impossible de charger les avis.")
        } finally {
            setLoading(false)
        }
    }, [page])

    useEffect(() => {
        load()
    }, [load])

    const changePage = (newPage: number) => {
        setPage(newPage)
    }

    return {
        reviews,
        totalReviews,
        averageRate,
        loading,
        error,
        page,
        totalPages,
        changePage,
        refresh: load,
        setError
    }

}