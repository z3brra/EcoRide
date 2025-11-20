import { useState, useCallback } from "react"
import { updateUser } from "@services/userService"
import { useAuth } from "@provider/AuthContext"

export function useUpdateProfile() {
    const { user, setUser } = useAuth()
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const updateProfile = useCallback(
        async (payload: { pseudo?: string; oldPassword?: string; newPassword?: string }) => {
            try {
                setLoading(true)
                setError(null)
                setSuccess(null)

                if (!user) {
                    throw new Error("Utilisateur non authentifié.")
                }

                await updateUser(payload)
                setSuccess("Profil mis à jour avec succès.")

                if (payload.pseudo) {
                    setUser((prev: any) => ({ ...prev, pseudo: payload.pseudo }))
                }
            } catch (error: any) {
                setError("Une erreur est survenue.")
            } finally {
                setLoading(false)
            }
        }, [user, setUser]
    )

    return {
        updateProfile,
        loading,
        error,
        success,
        setError,
        setSuccess
    }

}