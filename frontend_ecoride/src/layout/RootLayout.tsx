import { Outlet } from "react-router-dom"

export function RootLayout() {

    return (
        <>

            <main className="main-content">
                <Outlet />
            </main>
        </>
    )
}