import { useState, useCallback } from "react"
import { useNavigate } from "react-router-dom"

import type { RegisterUserPayload } from "@models/user"
import { registerUser } from "@services/registerService"

export function useRegister() {
    const navigate = useNavigate()

    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const register = useCallback(async (payload: RegisterUserPayload) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await registerUser(payload)

            setSuccess("Compte crée avec succès.")
            navigate('/')
        } catch (error: any) {
            setError("Une erreur est survenue lors de l'inscription.")
        } finally {
            setLoading(false)
        }
    }, [navigate])

    return {
        register,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}