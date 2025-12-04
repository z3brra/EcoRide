import { useState } from "react"
import { moderateEmployeeReview } from "@services/reviewService"

export function useModerateEmployeeReview() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const validateReview = async (uuid: string): Promise<boolean> => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await moderateEmployeeReview(uuid, "validate")
            setSuccess("Avis validé avec succès.")
            return true
        } catch {
            setError("Impossble de valider cet avis.")
            return false
        } finally {
            setLoading(false)
        }
    }

    const refuseReview = async (uuid: string): Promise<boolean> => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await moderateEmployeeReview(uuid, "refuse")
            setSuccess("Avis refusé avec succès.")
            return true
        } catch {
            setError("Impossible de refuser cet avis.")
            return false
        } finally {
            setLoading(false)
        }
    }

    return {
        loading,
        error,
        success,
        validateReview,
        refuseReview,
        setError,
        setSuccess
    }
}