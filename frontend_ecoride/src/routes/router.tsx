import { createBrowserRouter, Outlet } from 'react-router-dom'

import { PUBLIC_ROUTES, PROFILE_ROUTES } from "@routes/paths"

import { RootLayout } from '@layout/RootLayout'

import { AuthProvider } from '@provider/AuthContext'
import { SettlementProvider } from '@provider/SettlementContext'

import { RequireAuth } from "@components/auth/RequireAuth"

import { Home } from '@pages/Home'

import { Drives } from '@pages/Drives/Drives'
import { DriveDetail } from '@pages/Drives/DriveDetail'

import { Contact } from '@pages/Contact'

import { Login } from '@pages/auth/Login'

import { Register } from '@pages/auth/Register'

import { Profile } from '@pages/Profile/Profile'
import { ProfileDriveDetail } from '@pages/Profile/Driver/ProfileDriveDetail'

// export const router = createBrowserRouter([
//     {
//         element: (
//             <AuthProvider>
//                 <SettlementProvider>
//                     <Outlet />
//                 </SettlementProvider>
//             </AuthProvider>
//         ),
//         children: [
//             {
//                 path: PUBLIC_ROUTES.HOME,
//                 element: <RootLayout />,
//                 children: [
//                     { index: true, element: <Home /> },
//                     { 
//                         path: PUBLIC_ROUTES.DRIVES.REL,
//                         children: [
//                             { index: true, element: <Drives /> },
//                             { path: PUBLIC_ROUTES.DRIVES.DETAIL_PATTERN, element: <DriveDetail />}
//                         ]
//                     },

//                     { path: PUBLIC_ROUTES.CONTACT, element: <Contact /> },
//                     { path: PUBLIC_ROUTES.LOGIN, element: <Login /> },
//                     { path: PUBLIC_ROUTES.REGISTER, element: <Register /> },
//                 ]
//             },
//             {
//                 path: PROFILE_ROUTES.PROFILE,
//                 element: (
//                     <RequireAuth>
//                         <RootLayout />
//                     </RequireAuth>
//                 ),
//                 children: [
//                     { index: true, element: <Profile /> },
//                     {
//                         path: PROFILE_ROUTES.DRIVES.REL,
//                         children: [
//                             { path: PROFILE_ROUTES.DRIVES.DETAIL_PATTERN, element: <ProfileDriveDetail /> }
//                         ]
//                     }
//                 ]
//             }
//         ]
//     }
// ])

export const router = createBrowserRouter([
    {
        element: (
            <AuthProvider>
                <SettlementProvider>
                    <RootLayout />
                </SettlementProvider>
            </AuthProvider>
        ),
        handle: {
            title: "EcoRide - Plateforme de covoiturage écologique"
        },
        children: [
            // PUBLIC ROUTES
            { 
                index: true,
                element: <Home />,
                handle: {
                    title: "EcoRide - Acceuil",
                },
            },

            {
                path: PUBLIC_ROUTES.DRIVES.REL,
                children: [
                    {
                        index: true,
                        element: <Drives />,
                        handle: {
                            title: "EcoRide - Rechercher un trajet"
                        }
                    },
                    {
                        path: PUBLIC_ROUTES.DRIVES.DETAIL_PATTERN,
                        element: <DriveDetail />,
                        handle: {
                            title: "EcoRide - Détail du trajet"
                        }
                    }
                ]
            },

            {
                path: PUBLIC_ROUTES.CONTACT,
                element: <Contact />,
                handle: {
                    title: "EcoRide - Contact"
                },
            },
            {
                path: PUBLIC_ROUTES.LOGIN,
                element: <Login />,
                handle: {
                    title: "EcoRide - Connexion"
                }
            },
            {
                path: PUBLIC_ROUTES.REGISTER,
                element: <Register />,
                handle: {
                    title: "EcoRide - Inscription"
                }
            },

            // PRIVATE ROUTES
            {
                path: PROFILE_ROUTES.PROFILE,
                element: (
                    <RequireAuth>
                        <Outlet />
                    </RequireAuth>
                ),
                handle: {
                    title: "EcoRide - Mon espace"
                },
                children: [
                    {
                        index: true,
                        element: <Profile />,
                        handle: {
                            title: "EcoRide - Mon profil"
                        }
                    },
                    {
                        path: PROFILE_ROUTES.DRIVES.REL,
                        children: [
                            {
                                path: PROFILE_ROUTES.DRIVES.DETAIL_PATTERN,
                                element: <ProfileDriveDetail />,
                                handle: {
                                    title: "EcoRide - Détail de mon covoiturage"
                                }
                            }
                        ]
                    }
                ]
            }
        ]
    }
])
