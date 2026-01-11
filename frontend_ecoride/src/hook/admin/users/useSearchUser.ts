import { useState, useCallback } from "react"

import { searchUser } from "@services/adminService"
import type { ReadUserResponse } from "@models/user"

export function useSearchUser() {
    const [user, setUser] = useState<ReadUserResponse | null>(null)

    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const [hasSearched, setHasSearched] = useState<boolean>(false)

    const search = useCallback(async (email: string) => {
        const trimmed = email.trim().toLowerCase()
        if (!trimmed) {
            return
        }

        setLoading(true)
        setError(null)
        setHasSearched(true)

        try {
            const response: ReadUserResponse = await searchUser({ email: trimmed })
            setUser(response)
            return response
        } catch (error: any) {
            setError("Une erreur est survenur lors de  la recherche.")
            setUser(null)
            return null
        } finally {
            setLoading(false)
        }
    }, [])

    const reset = useCallback(() => {
        setUser(null)
        setHasSearched(false)
        setError(null)
    }, [])

    return {
        user,
        hasSearched,

        search,
        reset,

        loading,
        error,
        setError
    }
}