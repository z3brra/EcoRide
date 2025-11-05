import {
    createContext,
    useContext,
    useState,
    useEffect,
} from "react"
import type { ReactNode } from "react"

import { useNavigate } from "react-router-dom"
import { postRequest, getRequest } from "@api/request"
import { Endpoints } from "@api/endpoints"

import type {
    LoginResponse,
    LogoutResponse,
    CurrentUserResponse,
} from "@models/user"

import {
    clearAttempts,
    isLocked,
    incrementAttempts,
    getRetryInMinutes,
} from '@utils/loginLock'

interface AuthContextType {
    user: CurrentUserResponse | null
    setUser: React.Dispatch<React.SetStateAction<CurrentUserResponse | null>>
    isAuthenticated: boolean
    loading: boolean
    hasRole: (role: string) => boolean
    hasAnyRole: (...role: string []) => boolean
    login: (email: string, password: string) => Promise<void>
    logout: () => void
}

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export function AuthProvider({ children }: { children: ReactNode}) {
    const navigate = useNavigate()

    const [user, setUser] = useState<CurrentUserResponse | null>(null)
    const [loading, setLoading] = useState<boolean>(true)

    const isAuthenticated = Boolean(user)

    const hasRole = (role: string) => user?.roles.includes(role) ?? false
    const hasAnyRole = (...checks: string[]) => Boolean(user && checks.some(role => user.roles.includes(role)))

    useEffect(() => {
        getRequest<CurrentUserResponse>(Endpoints.USER)
            .then(setUser)
            .catch(() => setUser(null))
            .finally(() => setLoading(false))
    }, [])

    const login = async (email: string, password: string) => {
        if (isLocked()) {
            const retryIn = getRetryInMinutes()
            throw new Error(
                `Trop de tentatives. Veuillez réessayer dans ${retryIn} minute(s).`
            )
        }

        try {
            await postRequest<{ username: string; password: string }, LoginResponse>(
                Endpoints.LOGIN,
                { username: email, password }
            )

            clearAttempts()

            const currentUser = await getRequest<CurrentUserResponse>(Endpoints.USER)
            setUser(currentUser)
            navigate('/')
        } catch (error: any) {
            incrementAttempts()
            throw error
        }
    }

    const logout = async () => {
        try {
            await postRequest<{}, LogoutResponse>(
                Endpoints.LOGOUT,
                {}
            )
        } finally {
            setUser(null)
            navigate('/')
        }
    }

    return (
        <AuthContext.Provider
            value={{
                user,
                setUser,
                isAuthenticated,
                loading,
                hasRole,
                hasAnyRole,
                login,
                logout
            }}
        >
            {children}
        </AuthContext.Provider>
    )
}

export function useAuth(): AuthContextType {
    const context = useContext(AuthContext)
    if (!context) {
        throw new Error('useAuth must be used within AuthProvider')
    }
    return context
}