import { useState, useCallback } from "react"

import { createEmployee } from "@services/adminService"
import type { CreateEmployee, CreateEmployeeResponse } from "@models/user"

export function useCreateEmployee() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const create = useCallback(async (payload: CreateEmployee): Promise<CreateEmployeeResponse | null> => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            const response = await createEmployee(payload)
            setSuccess("Employé crée avec succès.")
            return response
        } catch (error: any) {
            setError(`Une erreur est survenue lors de la création de l'employé : ${payload.pseudo}`)
            return null
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        create,
        loading,
        error,
        success,
        setError,
        setSuccess
    }

}