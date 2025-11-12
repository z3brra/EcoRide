import { useState, useCallback } from "react"
import { deleteVehicle } from "@services/vehicleService"

export function useDeleteVehicle() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const remove = useCallback(async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await deleteVehicle(uuid)
            setSuccess("Véhicule supprimé avec succèss.")
        } catch (error: any) {
            setError("Une erreur est survenue lors de la suppression du véhicule.")
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        remove,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}