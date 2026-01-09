import { useState, useCallback } from "react"

import { unbanUser } from "@services/adminService"

export function useUnbanUser() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = useCallback(async (userUuid: string): Promise<boolean> => {
        if (!userUuid) {
            setError("UUID utilisateur manquant")
            return false
        }

        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await unbanUser(userUuid)
            setSuccess("Utilisateur débanni avec succès.")
            return true
        } catch (error: any) {
            setError("Une erreur s'est produite lors du débanissement de l'utilsateur.")
            return false
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        submit,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}