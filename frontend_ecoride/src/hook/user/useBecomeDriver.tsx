import { useState, useCallback } from "react"
import { becomeDriver } from "@services/userService"
import { useAuth } from "@provider/AuthContext"

export function useBecomeDriver() {
    const { user, setUser } = useAuth()

    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const activateDriver = useCallback(async () => {
        try {
            if (!user) {
                throw new Error("Utilisateur non authentifié.")
            }
            setLoading(true)
            setError(null)
            setSuccess(null)

            const updatedUser = await becomeDriver()

            setUser(updatedUser)
            setSuccess("Félicitations, vous êtes maintenant chauffeur !")
        } catch (error: any) {
            setError("Une erreur est survenue lors de l'activation du compte chauffeur.")
        } finally {
            setLoading(false)
        }
    }, [user, setUser])

    return {
        activateDriver,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}