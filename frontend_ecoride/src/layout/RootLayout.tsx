import { NavBar } from "@components/common/Navbar/NavBar"
import { Outlet } from "react-router-dom"

export function RootLayout() {

    return (
        <>
            <NavBar />
            <main className="main-content">
                <Outlet />
            </main>
        </>
    )
}