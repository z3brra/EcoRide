import { useCallback, useState } from "react";

import { sendContactMessage } from "@services/contactService";
import type { ContactPayload } from "@models/contact";

export function useContact() {
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState<string | null>(null)

    const submit = useCallback(async (payload: ContactPayload) => {
        setLoading(true)
        setError(null)
        setSuccess(null)

        try {
            await sendContactMessage(payload)
            setSuccess("Votre message a bien été envoyé.")
            return true
        } catch (error: any) {
            setError("Une erreur est survenue lors de l'envoi du message.")
            return false
        } finally {
            setLoading(false)
        }
    }, [])

    return {
        submit,
        loading,
        error,
        success,
        setError,
        setSuccess
    }
}