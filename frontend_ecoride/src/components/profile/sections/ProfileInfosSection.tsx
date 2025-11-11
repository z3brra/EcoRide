import type { JSX } from "react"
import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { useUpdateProfile } from "@hook/user/useUpdateProfile"
import { useBecomeDriver } from "@hook/user/useBecomeDriver"

import type { CurrentUserResponse } from "@models/user"

type Props = {
    user: CurrentUserResponse
    isDriver: boolean
}

export function ProfilInfosSection({
    user,
    isDriver
}: Props): JSX.Element {
    const [pseudo, setPseudo] = useState(user.pseudo)
    const {
        updateProfile,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useUpdateProfile()

    const {
        activateDriver,
        loading: driverLoading,
        error: driverError,
        success: driverSuccess,
        setError: setDriverError,
        setSuccess: setDriverSuccess,
    } = useBecomeDriver()

    const handleSavePseudo = async () => {
        if (pseudo.trim() === "") {
            return setError("Le pseudo ne peut pas être vide.")
        }
        await updateProfile({ pseudo })
    }

    const handleBecomeDriver = async () => {
        await activateDriver()
    }

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            { success && (
                <MessageBox variant="success" message={success} onClose={() => setSuccess(null)} />
            )}

            { driverError && (
                <MessageBox variant="error" message={driverError} onClose={() => setDriverError(null)} />
            )}

            { driverSuccess && (
                <MessageBox variant="success" message={driverSuccess} onClose={() => setDriverSuccess(null)} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <h3 className="text-subtitle text-primary text-left">
                        Informations personnelles
                    </h3>
                    <p className="text-small text-silent text-left">
                        Mettez à jour les informations relatives à votre compte.
                    </p>

                    <Input
                        label="Pseudo"
                        value={pseudo}
                        onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPseudo(event.currentTarget.value)}
                    />
                    <div className="profile__actions">
                        { !isDriver && (
                            <Button
                                variant="secondary"
                                disabled={driverLoading}
                                onClick={handleBecomeDriver}
                            >
                                {driverLoading ? "Activation..." : "Devenir chauffeur"}
                            </Button>
                        )}
                        <Button
                            variant="primary"
                            disabled={loading}
                            onClick={handleSavePseudo}
                        >
                            Sauvegarder
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </>
    )
}