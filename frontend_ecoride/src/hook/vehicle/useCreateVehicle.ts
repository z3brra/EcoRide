import { useState, useCallback } from "react"
import { createVehicle } from "@services/vehicleService"
import type { CreateVehicle, Vehicle } from "@models/vehicle"

export function useCreateVehicle() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const create = useCallback(async (payload: CreateVehicle): Promise<Vehicle | null> => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            const response = await createVehicle(payload)
            setSuccess("Véhicule créer avec succès.")
            return response
        } catch (error: any) {
            setError("Une erreur est survenue lors de la création du véhicule")
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