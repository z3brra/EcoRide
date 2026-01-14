import { useState, useCallback } from "react"

import { banUser } from "@services/adminService"

export function useBanUser() {
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
            await banUser(userUuid)
            setSuccess("Utilisateur banni avec succès.")
            return true
        } catch (error: any) {
            setError("Une erreur s'est produite lors du banissement de l'utilsateur.")
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