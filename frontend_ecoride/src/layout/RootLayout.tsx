import { Footer } from "@components/common/Footer/Footer"
import { NavBar } from "@components/common/Navbar/NavBar"
import { Outlet } from "react-router-dom"

import { useSettlement } from "@provider/SettlementContext"
import { SettlementModal } from "@components/settlement/SettlementModal"

export function RootLayout() {
    const {
        isOpen,
        driveUuid,
        loading,
        error,
        confirm,
        dispute,
        close
    } = useSettlement()

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
        </>
    )
}