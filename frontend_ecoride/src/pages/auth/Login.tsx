import type { JSX } from "react"
import { useState } from "react"

import { useAuth } from "@provider/AuthContext"

import { Link } from "react-router-dom"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { LogIn, Leaf} from "lucide-react"
import { Section } from "@components/common/Section/Section"
import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { CardIcon } from "@components/common/Card/CardIcon"
import { SectionHeader } from "@components/common/Section/SectionHeader"
import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { PUBLIC_ROUTES } from "@routes/paths"

export function Login(): JSX.Element {
    const { login } = useAuth()
    const [email, setEmail] = useState<string>("")
    const [password, setPassword] = useState<string>("")

    const [isLoading, setIsLoading] = useState<boolean>(false)

    const [error, setError] = useState<string | null>(null)
    const [warning, setWarning] = useState<string | null>(null)

    const [fieldErrors, setFieldErrors] = useState<{
        email?: string
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
        setFieldErrors(errors)
        return Object.keys(errors).length === 0
    }

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault()
        setError(null)

        if (!validateFields()) {
            return
        }

        setIsLoading(true)
        try {
            await login(email, password)
        } catch (error: any) {
            if (error.message.startsWith('Trop de tentatives')) {
                setWarning(error.message)
            } else if (error.response?.status === 401) {
                setError("Identifiants invalides.")
            } else {
                setError("Une erreur est survenue, merci de réessayer.")
            }
        } finally {
            setIsLoading(false)
        }
    }

    return (
        <Section id="login" className="login">
            <Card className="login__card">
                <CardIcon icon={<Leaf size={30}/>} />
                <SectionHeader
                    title="Bienvenue"
                    description="Connectez-vous à votre compte Ecoride"
                    titleVariant="subtitle"
                    descriptionVariant="content"
                    align="center"
                />
                <CardContent>
                    <form className="card__form" noValidate onSubmit={handleSubmit}>

                        { error && (
                            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
                        )}

                        { warning && (
                            <MessageBox variant="warning" message={warning} onClose={() => setWarning(null)} />
                        )}

                        
                        <Input
                            type="email"
                            label="Email"
                            placeholder="votre.mail@email.com"
                            required
                            className="text-content"
                            value={email}
                            onChange={(e: React.ChangeEvent<HTMLInputElement>) => setEmail(e.currentTarget.value)}
                            errorText={fieldErrors.email}
                        />
                        <Input
                            type="password"
                            label="Mot de passe"
                            placeholder="Votre email"
                            required
                            className="text-content"
                            value={password}
                            onChange={(e: React.ChangeEvent<HTMLInputElement>) => setPassword(e.currentTarget.value)}
                            errorText={fieldErrors.password}
                        />
                        <Button
                            variant="primary"
                            type="submit"
                            onClick={() => {}}
                            disabled={isLoading}
                            icon={<LogIn />}
                            className="text-content"
                        >
                            { isLoading ? "Connexion..." : "Se connecter"}
                        </Button>

                        <div className="auth__footer">
                            <p className="text-small text-silent">
                                Pas encore de compte ?{" "}
                                <Link to={PUBLIC_ROUTES.REGISTER} className="auth__link text-small">
                                    Créer un compte
                                </Link>
                            </p>
                        </div>

                    </form>
                </CardContent>
            </Card>
        </Section>
    )
}