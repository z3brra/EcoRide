import type { JSX } from "react"
import { useState } from "react"

import { Link } from "react-router-dom"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { UserPlus, Leaf} from "lucide-react"
import { Section } from "@components/common/Section/Section"
import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { CardIcon } from "@components/common/Card/CardIcon"
import { SectionHeader } from "@components/common/Section/SectionHeader"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { PUBLIC_ROUTES } from "@routes/paths"

import { useRegister } from "@hook/auth/useRegister"

export function Register(): JSX.Element {
    const [pseudo, setPseudo] = useState<string>("")
    const [email, setEmail] = useState<string>("")
    const [password, setPassword] = useState<string>("")
    const [confirmPassword, setConfirmPassword] = useState<string>("")

    const {
        register,
        loading,
        error,
        setError
    } = useRegister()

    const [fieldErrors, setFieldErrors] = useState<{
        pseudo?: string
        email?: string
        confirmPassword?: string
        password?: string
    }>({})

    const validateFields = () => {
        const errors: typeof fieldErrors = {}
        if (!email.trim()) {
            errors.email = "L'adresse email est requise..."
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = "Adresse email invalide."
        }
        if (!password) {
            errors.password = "Le mot de passe est requis."
        }
        if (!pseudo.trim()) {
            errors.pseudo = "Le pseudo est requis."
        }
        if (!confirmPassword) {
            errors.confirmPassword = "La confirmation du mot de passe est requise."
        }
        if (password !== confirmPassword) {
            errors.confirmPassword = "Différence entre la confirmation et le mot de passe."
        }
        setFieldErrors(errors)
        return Object.keys(errors).length === 0
    }

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault()
        setError(null)

        if (!validateFields()) {
            return
        }

        await register({
            pseudo: pseudo.trim(),
            email: email.trim(),
            password
        })
    }

    return (
        <Section id="register" className="register">
            <Card className="register__card">
                <CardIcon icon={<Leaf size={30} />} />
                <SectionHeader
                    title="Créer un compte"
                    description="Inscrivez-vous pour rejoindre Ecoride"
                    titleVariant="subtitle"
                    descriptionVariant="content"
                    align="center"
                />

                <CardContent>
                    <form className="card__form" noValidate onSubmit={handleSubmit}>

                        { error && (
                            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
                        )}

                        <Input
                            type="text"
                            label="Pseudo"
                            placeholder="Votre pseudo"
                            required
                            className="text-content"
                            value={pseudo}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPseudo(event.currentTarget.value)}
                            errorText={fieldErrors.pseudo}
                        />

                        <Input
                            type="email"
                            label="Email"
                            placeholder="exemple@email.com"
                            required
                            className="text-content"
                            value={email}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setEmail(event.currentTarget.value)}
                            errorText={fieldErrors.email}
                        />

                        <Input
                            type="password"
                            label="Mot de passe"
                            placeholder="Votre mot de passe"
                            required
                            className="text-content"
                            value={password}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPassword(event.currentTarget.value)}
                            errorText={fieldErrors.password}
                        />

                        <Input
                            type="password"
                            label="Confirmation du mot de passe"
                            placeholder="Confirmez votre mot de passe"
                            required
                            className="text-content"
                            value={confirmPassword}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setConfirmPassword(event.currentTarget.value)}
                            errorText={fieldErrors.confirmPassword}
                        />

                        <Button
                            variant="primary"
                            type="submit"
                            onClick={() => {}}
                            disabled={loading}
                            icon={<UserPlus size={18} />}
                            className="text-content"
                        >
                            { loading ? "Inscription..." : "S'inscrire" }
                        </Button>

                        <div className="auth__footer">
                            <p className="text-small text-silent">
                                Déjà un compte ?{" "}
                                <Link
                                    to={PUBLIC_ROUTES.LOGIN}
                                    className="auth__link text-small"
                                >
                                    Se connecter
                                </Link>
                            </p>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </Section>
    )
}