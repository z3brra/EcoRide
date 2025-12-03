import { useState } from "react"
import { createDrive } from "@services/driveService"
import type { CreateDrivePayload } from "@models/drive"

export function useCreateDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (payload: CreateDrivePayload) => {
        setLoading(true)
        setError(null)
        setSuccess(null)
        try {
            await createDrive(payload)
            setSuccess("Trajet crée avec succès.")
        } catch (error: any) {
            setError("Impossible de créer le trajet. Veuillez réessayer.")
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