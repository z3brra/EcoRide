import { useState } from "react"
import { finishDrive } from "@services/driveService"

export function useFinishDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await finishDrive(uuid)
            setSuccess("Le trajet a été terminé avec succès.")
        } catch (error: any) {
            setError("Impossible de terminer le trajet")
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