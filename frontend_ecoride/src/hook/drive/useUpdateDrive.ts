import { useState } from "react"
import type { UpdateDrivePayload } from "@models/drive"
import { updateDrive } from "@services/driveService"

export function useUpdateDrive() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = async (uuid: string, payload: UpdateDrivePayload) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await updateDrive(uuid, payload)
            setSuccess("Le trajet a bien été mis à jour.")
        } catch (error: any) {
            setError("Une erreur est survenue lors de la modification du trajet.")
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