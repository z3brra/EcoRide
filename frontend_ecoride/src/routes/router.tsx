import { createBrowserRouter, Outlet } from 'react-router-dom'

import { PUBLIC_ROUTES } from "@routes/paths"

import { RootLayout } from '@layout/RootLayout'

import { AuthProvider } from '@provider/AuthContext'

import { Home } from '@pages/Home'
import { Contact } from '@pages/Contact'

export const router = createBrowserRouter([
    {
        element: (
            <AuthProvider>
                <Outlet />
            </AuthProvider>
        ),
        children: [
            {
                path: PUBLIC_ROUTES.HOME,
                element: <RootLayout />,
                children: [
                    { index: true, element: <Home /> },
                    { path: PUBLIC_ROUTES.CONTACT, element: <Contact /> },
                ]
            }
        ]
    }
])