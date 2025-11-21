import { useState, useEffect } from "react"
import { fetchAllVehicles } from "@services/vehicleService"
import type { Vehicle } from "@models/vehicle"

export function useAllVehicle() {
    const [data, setData] = useState<Vehicle[]>([])
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const load = async () => {
        setLoading(true)
        setError(null)

        try {
            const response = await fetchAllVehicles()
            setData(response.data)
        } catch (error: any) {
            setError("Impossible de charger les véhicules.")
        } finally {
            setLoading(false)
        }
    }

    useEffect(() => {
        load()
    }, [])

    return {
        data,
        loading,
        error,
        reload: load,
        setError
    }
}