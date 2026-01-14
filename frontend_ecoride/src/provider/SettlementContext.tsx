import { createContext, useContext, useState, useCallback } from "react"
import { useNavigate } from "react-router-dom"

import { useSettlementActions } from "@hook/drive/useSettlementActions"
import { useSettlementLock } from "@hook/settlement/useSettlementLock"

import { useCreateReview } from "@hook/review/useCreateReview"


type SettlementContextType = {
    isOpen: boolean
    driveUuid: string | null
    showReview: boolean

    loading: boolean
    error: string | null

    reviewLoading: boolean
    reviewError: string | null

    open: (uuid: string) => void
    close: () => void

    confirm: () => Promise<void>
    dispute: (comment: string) => Promise<void>

    submitReview: (rate: number, comment: string) => Promise<void>
    closeReview: () => void

    setError: (message: string | null) => void
    setReviewError: (message: string | null) => void
}

const SettlementContext = createContext<SettlementContextType | null>(null)

export function SettlementProvider({ children }: { children: React.ReactNode }) {
    const navigate = useNavigate()

    const [isOpen, setIsOpen] = useState<boolean>(false)
    const [showReview, setShowReview] = useState<boolean>(false)

    const [driveUuid, setDriveUuid] = useState<string | null>(null)

    const {
        confirm: confirmAction,
        dispute: disputeAction,
        loading,
        error,
        setError,
    } = useSettlementActions()

    const {
        submit: createReview,
        loading: reviewLoading,
        error: reviewError,
        setError: setReviewError,
    } = useCreateReview()

    const open = useCallback((uuid: string) => {
        setDriveUuid(uuid)
        setIsOpen(true)
        setShowReview(false)

        setError(null)
        setReviewError(null)
    }, [])

    const close = useCallback(() => {
        setIsOpen(false)
    }, [])

    const closeReview = useCallback(() => {
        setShowReview(false)
        setDriveUuid(null)
    }, [])

    useSettlementLock((uuid) => {
        open(uuid)
    })

    const refresh = () => {
        navigate(0)
    }

    const confirm = async () => {
        if (!driveUuid) {
            return
        }
        try {
            await confirmAction(driveUuid)
            close()
            setShowReview(true)
        } catch {}
    }

    const dispute = async (comment: string) => {
        if (!driveUuid) {
            return
        }
        try {
            console.log(`comment ${comment}`)
            await disputeAction(driveUuid, comment)
            close()
            setDriveUuid(null)
            refresh()
        } catch {}
    }

    const submitReview = async (rate: number, comment: string) => {
        if (!driveUuid) {
            return
        }

        try {
            await createReview({ driveUuid, rate, comment })
            closeReview()
            refresh()
        } catch {}
    }

    return (
        <SettlementContext.Provider value={{
            isOpen,
            driveUuid,
            showReview,

            loading,
            error,

            reviewLoading,
            reviewError,

            open,
            close,
            confirm,
            dispute,
            submitReview,
            closeReview,

            setError,
            setReviewError,
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