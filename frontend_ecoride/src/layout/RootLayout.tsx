import { useEffect } from "react"
import { Outlet, useMatches } from "react-router-dom"

import { Footer } from "@components/common/Footer/Footer"
import { NavBar } from "@components/common/Navbar/NavBar"


import { useSettlement } from "@provider/SettlementContext"
import { SettlementModal } from "@components/settlement/SettlementModal"
import { CreateReviewModal } from "@components/review/CreateReviewModal"

type RouteHandle = {
    title?: string
}

export function RootLayout() {
    const {
        isOpen,
        driveUuid,
        showReview,
        loading,
        error,
        confirm,
        dispute,
        submitReview,
        close,
        closeReview,
    } = useSettlement()

    const rawMatches = useMatches()
    const matches = rawMatches as Array<typeof rawMatches[number] & { handle?: RouteHandle}>

    useEffect(() => {
        const matchWithTitle = [...matches]
        .reverse()
        .find((m) => m.handle?.title)

        if (matchWithTitle?.handle?.title) {
            document.title = matchWithTitle.handle.title
        }
    }, [matches])

    return (
        <>
            <NavBar />
            <main className="main-content">
                <Outlet />
                <Footer />
            </main>

            { isOpen && driveUuid && (
                <SettlementModal
                    isOpen={isOpen}
                    driveUuid={driveUuid}
                    onClose={close}
                    onConfirm={confirm}
                    onDispute={dispute}
                    loading={loading}
                    error={error}
                />
            )}

            { showReview && driveUuid && (
                <CreateReviewModal
                    isOpen={showReview}
                    onClose={closeReview}
                    onSubmit={(rate, comment) => submitReview(rate, comment)}
                />
            )}
        </>
    )
}