import { useState, useCallback } from "react"
import { updateVehicle } from "@services/vehicleService"

import type { UpdateVehicle, Vehicle } from "@models/vehicle"

export function useUpdateVehicle() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const update = useCallback(async (uuid: string, payload: UpdateVehicle): Promise<Vehicle | null> => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            const response = await updateVehicle(uuid, payload)
            setSuccess("Véhicule mis à jour avec succès.")
            return response
        } catch (error: any) {
            setError("Une erreur est survenue lors de la mise à jour du véhicule.")
            return null
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        update,
        loading,
        error,
        success,
        setError,
        setSuccess,
    }
}
