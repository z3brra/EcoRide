import type { JSX } from "react"
import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { useUpdateProfile } from "@hook/user/useUpdateProfile"

export function ProfileSecuritySection(): JSX.Element {
    const [oldPassword, setOldPassword] = useState<string>("")
    const [newPassword, setNewPassword] = useState<string>("")
    const [confirmPassword, setConfirmPassword] = useState<string>("")

    const {
        updateProfile,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useUpdateProfile()

    const handleSavePassword = async () => {
        if (!oldPassword || !newPassword || !confirmPassword) {
            return setError("Tous les champs sont requis.")
        }
        if (newPassword !== confirmPassword) {
            return setError("Les mots de passe ne correspondent pas.")
        }
        await updateProfile({ oldPassword, newPassword })
        setOldPassword("")
        setNewPassword("")
        setConfirmPassword("")
    }


    return (
        <>
        { error && (
            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
        )}

        { success && (
            <MessageBox variant="success" message={success} onClose={() => setSuccess(null)} />
        )}

        <Card className="profile__section">
            <CardContent  gap={1}>
                <h3 className="text-subtitle text-primary text-left">
                    Modifier le mot de passe
                </h3>
                <p className="text-small text-silent text-left">
                    Mettez à jour votre mot de passe pour sécuriser votre compte
                </p>

                <Input
                    type="password"
                    label="Mot de passe actuel"
                    value={oldPassword}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setOldPassword(event.currentTarget.value)}
                />
                <Input
                    type="password"
                    label="Nouveau mot de passe"
                    value={newPassword}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setNewPassword(event.currentTarget.value)}
                />
                <Input
                    type="password"
                    label="Confirmer le mot de passe"
                    value={confirmPassword}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setConfirmPassword(event.currentTarget.value)}
                />
                <div className="profile__actions">
                    <Button
                        variant="primary"
                        disabled={loading}
                        onClick={handleSavePassword}
                    >
                        Sauvegarder
                    </Button>
                </div>
            </CardContent>
        </Card>

        </>
    )
}