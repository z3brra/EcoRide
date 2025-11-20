import { createBrowserRouter, Outlet } from 'react-router-dom'

import { PUBLIC_ROUTES, PROFILE_ROUTES } from "@routes/paths"

import { RootLayout } from '@layout/RootLayout'

import { AuthProvider } from '@provider/AuthContext'
import { RequireAuth } from "@components/auth/RequireAuth"

import { Home } from '@pages/Home'

import { Drives } from '@pages/Drives/Drives'
import { DriveDetail } from '@pages/Drives/DriveDetail'

import { Contact } from '@pages/Contact'

import { Login } from '@pages/auth/Login'

import { Register } from '@pages/auth/Register'

import { Profile } from '@pages/Profile/Profile'

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
                    { 
                        path: PUBLIC_ROUTES.DRIVES.REL,
                        children: [
                            { index: true, element: <Drives /> },
                            { path: PUBLIC_ROUTES.DRIVES.DETAIL_PATTERN, element: <DriveDetail />}
                        ]
                    },

                    { path: PUBLIC_ROUTES.CONTACT, element: <Contact /> },
                    { path: PUBLIC_ROUTES.LOGIN, element: <Login /> },
                    { path: PUBLIC_ROUTES.REGISTER, element: <Register /> },
                ]
            },
            {
                path: PROFILE_ROUTES.PROFILE,
                element: (
                    <RequireAuth>
                        <RootLayout />
                    </RequireAuth>
                ),
                children: [
                    { index: true, element: <Profile /> },
                ]
            }
        ]
    }
])