import { useState, useCallback } from "react"
import { moderateDriveDispute } from "@services/disputeService"
import type { ModerateDispute } from "@models/dispute"

export function useModerateDispute() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = useCallback(async (
        driveUuid: string,
        participantUuid: string,
        payload: ModerateDispute
    ) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await moderateDriveDispute(
                driveUuid,
                participantUuid,
                payload
            )
            setSuccess("Action effectuée avec succès.")
        } catch (error: any) {
            setError("Impossible de modérer le litige")
        } finally {
            setLoading(false)
        }
    }, [])

    const confirmDispute = useCallback(async (
        driveUuid: string,
        participantUuid: string
    ) => {
        return submit(driveUuid, participantUuid, {
            action: "confirm"
        })
    }, [submit])

    const refundDispute = useCallback(async(
        driveUuid: string,
        participantUuid: string,
        comment?: string
    ) => {
        const trimmed = (comment ?? "").trim()

        const payload: ModerateDispute = trimmed.length > 0
            ? { action: "refund", comment: trimmed }
            : { action: "refund" }

        return submit(driveUuid, participantUuid, payload)
    }, [submit])

    return {
        loading,
        error,
        success,
        confirmDispute,
        refundDispute,
        setError,
        setSuccess
    }
}