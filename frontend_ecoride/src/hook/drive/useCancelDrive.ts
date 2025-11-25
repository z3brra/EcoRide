import { useState } from "react"
import { cancelOwnedDrive } from "@services/driveService"

export function useCancelDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await cancelOwnedDrive(uuid)
            setSuccess("Le trajet a été annulé avec succès.")
        } catch (error: any) {
            setError("Impossible d'annuler le trajet")
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