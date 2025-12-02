import { useState, useEffect, useCallback } from "react"

import type { AggregatedPref } from "@models/driverPreference"

import { 
    createCustomPreference,
    deleteCustomPreferences,
    fetchAllPref,
    updateFixedPreferences
} from "@services/driverPrefService"

export function usePreference() {
    const [prefs, setPrefs] = useState<AggregatedPref | null>(null)
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    const loadPrefs = useCallback(async () => {
        setLoading(true)
        setError(null)

        try {
            const prefResponse = await fetchAllPref()
            setPrefs(prefResponse)
        } catch (error: any) {
            setError("Impossible de charger les préférences")
        } finally {
            setLoading(false)
        }
    }, [])

    useEffect(() => {
        loadPrefs()
    }, [loadPrefs])

    const saveFixedprefs = async (animals: boolean, smoke: boolean) => {
        setLoading(true)
        setError(null)

        try {
            const updated = await updateFixedPreferences(animals, smoke)
            setPrefs(updated)
        } catch (error: any) {
            setError("Impossible de mettre à jour les préférences.")
        } finally {
            setLoading(false)
        }
    }

    const addCustomPref = async (label: string) => {
        if (!label.trim()) {
            return
        }
        setLoading(true)
        setError(null)
        try {
            const updated = await createCustomPreference(label.trim())
            setPrefs(updated)
        } catch (error: any) {
            setError("Impossible d'ajouter la préférence personnalisée.")
        } finally {
            setLoading(false)
        }
    }

    const removeCustomPref = async (uuid: string) => {
        setLoading(true)
        setError(null)
        try {
            await deleteCustomPreferences([uuid])
            await loadPrefs()
        } catch {
            setError("Impossible de supprimer la préférence personnalisée.")
        } finally {
            setLoading(false)
        }
    }

    return {
        prefs,
        loading,
        error,

        refresh: loadPrefs,
        saveFixedprefs,

        addCustomPref,
        removeCustomPref,

        setError,
    }
}