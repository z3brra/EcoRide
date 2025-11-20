import { useState, useEffect, useCallback } from "react"
import { useParams } from "react-router-dom"

import type { Drive } from "@models/drive"
import { fetchOneDrive } from "@services/driveService"

export function useDriveDetail() {
    const { uuid } = useParams<{ uuid: string }>()

    const [drive, setDrive] = useState<Drive | null>(null)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [notFound, setNotFound] = useState<boolean>(false)

    const loadDrive = useCallback(async () => {
        if (!uuid) {
            return
        }
        setLoading(true)
        setError(null)
        try {
            const driveResponse = await fetchOneDrive(uuid)
            setDrive(driveResponse)
            if (!driveResponse) {
                setNotFound(true)
            }
        } catch {
            setError("Impossible de charger le trajet")
        } finally {
            setLoading(false)
        }
    }, [uuid])

    useEffect(() => {
        loadDrive()
    }, [loadDrive])

    return {
        drive,

        loading,
        error,
        setError,
        notFound
    }
}