// import { useState } from "react"
// import { confirmDrive, disputeDrive } from "@services/driveService"

// export function useSettlementActions() {
//     const [lockedDriveUuid, setLockedDriveUuid] = useState<string | null>(null)
//     const [loading, setLoading] = useState<boolean>(false)
//     const [error, setError] = useState<string | null>(null)
//     const [success, setSuccess] = useState<string| null>(null)

//     const triggerLock = (uuid: string) => {
//         setLockedDriveUuid(uuid)
//     }

//     const confirm = async () => {
//         if (!lockedDriveUuid) {
//             return
//         }

//         setLoading(true)
//         setError(null)
//         setSuccess(null)

//         try {
//             await confirmDrive(lockedDriveUuid)
//             setSuccess("Trajet validé avec succès.")
//             setLockedDriveUuid(null)
//         } catch (error: any) {
//             setError("Impossible de valider le trajet")
//         } finally {
//             setLoading(false)
//         }
//     }

//     const dispute = async (comment: string) => {
//         if (!lockedDriveUuid) {
//             return
//         }

//         setLoading(true)
//         setError(null)
//         setSuccess(null)

//         try {
//             await disputeDrive(lockedDriveUuid, { comment })
//             setSuccess("Litige ouvert. Un employé vous contactera dans les plus brefs délais")
//             setLockedDriveUuid(null)
//         } catch (error: any) {
//             setError("Impossible d'ouvrir un litige")
//         } finally {
//             setLoading(false)
//         }
//     }

//     return {
//         lockedDriveUuid,
//         triggerLock,
//         confirm,
//         dispute,
//         loading,
//         error,
//         success,
//         setError,
//         setSuccess
//     }
// }

import { useState } from "react"
import { confirmDrive, disputeDrive } from "@services/driveService"

export function useSettlementActions() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const confirm = async (uuid: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await confirmDrive(uuid)
            setSuccess("Votre trajet a été confirmé.")
        } catch (error: any) {
            setError("Impossible de valider le trajet.")
            throw error
        } finally {
            setLoading(false)
        }
    }

    const dispute = async (uuid: string, comment: string) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            console.log(`hook : ${comment}`)
            await disputeDrive(uuid, comment)
            setSuccess("Votre litige a été envoyé. Un employé vous contactera dans les plus brefs délais")
        } catch (error: any) {
            setError("Impossible d'ouvrir un litige")
            throw error
        } finally {
            setLoading(false)
        }
    }

    return {
        loading,
        error,
        success,
        confirm,
        dispute,
        setError,
        setSuccess
    }
}