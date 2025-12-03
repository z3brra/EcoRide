import { useState } from "react"
import { createReview } from "@services/reviewService"
import type { CreateReview } from "@models/review"

export function useCreateReview() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (payload: CreateReview) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            const body: any = {
                driveUuid: payload.driveUuid,
                rate: payload.rate
            }

            if (payload.comment && payload.comment.trim().length > 0) {
                body.comment = payload.comment.trim()
            }
            await createReview(body)
            setSuccess("Avis enregistré avec succès.")
        } catch (error: any) {
            setError("Impossible d'envoyer l'avis.")
            throw error
        } finally {
            setLoading(false)
        }
    }

    return {
        submit,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}