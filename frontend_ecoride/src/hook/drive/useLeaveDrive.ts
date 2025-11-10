import { useState, useCallback } from "react"
import { leaveDrive } from "@services/driveService"

export function useLeaveDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const cancelBooking = useCallback(async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await leaveDrive(uuid)
            setSuccess("Réservation annulée avec succès.")
        } catch (error: any) {
            setError("Une erreur est survenue lors de l'annulation du trajet.")
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        cancelBooking,
        loading,
        error,
        success,
        setError,
        setSuccess,
    }
}