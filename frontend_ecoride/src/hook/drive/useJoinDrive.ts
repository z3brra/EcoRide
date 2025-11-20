import { useState, useCallback } from "react"
import { useNavigate } from "react-router-dom"

import { joinDrive } from "@services/driveService"
import { useAuth } from "@provider/AuthContext"
import { PUBLIC_ROUTES } from "@routes/paths"

export function useJoinDrive() {
    const navigate = useNavigate()

    const { user, isAuthenticated } = useAuth()

    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const join = useCallback(async (uuid: string, ownerUuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            if (!isAuthenticated) {
                navigate(PUBLIC_ROUTES.LOGIN)
                return
            }

            if (user?.uuid === ownerUuid) {
                throw new Error("Vous ne pouvez pas réserver votre propre trajet.")
            }

            await joinDrive(uuid)
            setSuccess("Réservation effectuée avec succès.")
        } catch (error: any) {
            setError(error.message || "Une erreur est survenue lors de la réservation.")
        } finally {
            setLoading(false)
        }
    }, [isAuthenticated, user])

    return {
        join,
        loading,
        error,
        setError,
        success,
        setSuccess,
    }
}