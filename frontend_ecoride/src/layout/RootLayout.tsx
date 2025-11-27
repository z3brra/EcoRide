import { Footer } from "@components/common/Footer/Footer"
import { NavBar } from "@components/common/Navbar/NavBar"
import { Outlet } from "react-router-dom"

import { useSettlement } from "@provider/SettlementContext"
import { SettlementModal } from "@components/settlement/SettlementModal"
import { CreateReviewModal } from "@components/review/CreateReviewModal"

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

    console.log("RootLayout", { showReview, driveUuid })

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