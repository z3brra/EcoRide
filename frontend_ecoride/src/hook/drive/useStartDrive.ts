import { useState } from "react"
import { startDrive } from "@services/driveService"

export function useStartDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await startDrive(uuid)
            setSuccess("Le trajet a bien été démarré.")
        } catch (error: any) {
            setError("Impossible de démarrer le trajet.")
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