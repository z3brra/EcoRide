import { createContext, useContext, useState, useCallback } from "react"
import { useNavigate } from "react-router-dom"

import { useSettlementActions } from "@hook/drive/useSettlementActions"
import { useSettlementLock } from "@hook/settlement/useSettlementLock"

type SettlementContextType = {
    isOpen: boolean
    driveUuid: string | null
    loading: boolean
    error: string | null
    success: string | null
    open: (uuid: string) => void
    close: () => void
    confirm: () => Promise<void>
    dispute: (comment: string) => Promise<void>
    setError: (message: string | null) => void
    setSuccess: (message: string | null) => void
}

const SettlementContext = createContext<SettlementContextType | null>(null)

export function SettlementProvider({ children }: { children: React.ReactNode }) {
    const navigate = useNavigate()

    const [isOpen, setIsOpen] = useState<boolean>(false)

    const [driveUuid, setDriveUuid] = useState<string | null>(null)

    const {
        confirm: confirmAction,
        dispute: disputeAction,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useSettlementActions()

    const open = useCallback((uuid: string) => {
        setDriveUuid(uuid)
        setIsOpen(true)
    }, [])

    const close = useCallback(() => {
        setIsOpen(false)
        setDriveUuid(null)
    }, [])

    useSettlementLock((uuid) => {
        open(uuid)
    })

    const refreshAfterSuccess = () => {
        navigate(0)
    }

    const confirm = async () => {
        if (!driveUuid) {
            return
        }
        await confirmAction(driveUuid)
        if (!error) {
            close()
            refreshAfterSuccess()
        }
    }

    const dispute = async (comment: string) => {
        if (!driveUuid) {
            return
        }
        await disputeAction(driveUuid, comment)
        if (!error) {
            close()
            refreshAfterSuccess()
        }
    }

    return (
        <SettlementContext.Provider value={{
            isOpen,
            driveUuid,
            loading,
            error,
            success,
            open,
            close,
            confirm,
            dispute,
            setError,
            setSuccess
        }}>
            {children}

        </SettlementContext.Provider>
    )
}

export function useSettlement() {
    const ctx = useContext(SettlementContext)
    if (!ctx) {
        throw new Error("UseSettlement must be used inside SettlementProvider")
    }
    return ctx
}