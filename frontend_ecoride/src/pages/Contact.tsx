import { useState } from "react"
import type { JSX } from "react"

import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

import { Card } from "@components/common/Card/Card"
import { CardIcon } from "@components/common/Card/CardIcon"
import { CardContent } from "@components/common/Card/CardContent"

import { Mail, MessageSquare } from "lucide-react"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"
import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { useContact } from "@hook/contact/useContact"

export function Contact(): JSX.Element {
    const [name, setName] = useState<string>("")
    const [email, setEmail] = useState<string>("")
    const [message, setMessage] = useState<string>("")

    const [fieldErrors, setFieldErrors] = useState<{
        name?: string
        email?: string
        message?: string
    }>({})

    const {
        submit,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useContact()

    const validateFields = () => {
        const errors: typeof fieldErrors = {}

        if (!name.trim()) {
            errors.name = "Le nom est requis."
        }

        if (!email.trim()) {
            errors.email = "L'adresse email est requise."
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = "L'adresse email est invalide."
        }

        if (!message.trim()) {
            errors.message = "Le message est requis."
        }

        setFieldErrors(errors)
        return Object.keys(errors).length === 0
    }

    const resestForm = () => {
        setName("")
        setEmail("")
        setMessage("")
        setFieldErrors({})
    }

    const handleSubmit = async (event: React.FormEvent) => {
        event.preventDefault()
        setError(null)
        setSuccess(null)

        if (!validateFields()) {
            return
        }

        const ok = await submit({
            name: name.trim(),
            email: email.trim(),
            message: message.trim(),
        })

        if (ok) {
            resestForm()
        }
    }

    return (
        <>
            <Section id="contact-header">
                <SectionHeader
                    title="Nous contacter"
                    description="Une question ou un commentaire ? Nous serions ravis de vous lire."
                    titleVariant="subtitle"
                    descriptionVariant="bigcontent"
                    breakOnComma
                    animate
                    align="center"
                />
            </Section>

            <Section id="contact-infos" className="contact-infos">
                <Card animate className="contact-infos__card">
                    <CardIcon icon={<Mail size={30}/>} />
                    <CardContent>
                        <h3 className="card__title">
                            Envoyez-nous un email
                        </h3>
                        <p className="card__description">
                            Envoyez-nous un email et nous vous répondrons dans les 24 heures.
                        </p>
                    </CardContent>
                    <CardContent>
                        <p className="card__footer text-content text-silent">contact@ecoride.eu</p>
                    </CardContent>
                </Card>

                <Card animate className="contact-infos__card">
                    <CardIcon icon={<MessageSquare size={30}/>} />
                    <CardContent>
                        <h3 className="card__title">
                            Support
                        </h3>
                        <p className="card__description">
                            Besoin d'aide ? Notre équipe d'assistance est là pour vous aider.
                        </p>
                    </CardContent>
                    <CardContent>
                        <p className="card__footer text-content text-silent">Lundi - Vendredi : 8h à 19h</p>
                    </CardContent>
                </Card>
            </Section>

            <Section id="contact-form" className="contact-form">
                <Card className="contact-form__card">
                    <CardContent>
                        <h3 className="card__title">
                            Envoyez-nous un message
                        </h3>
                        <p className="card__description">
                            Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.
                        </p>
                    </CardContent>
                    <CardContent>
                        <form className="card__form" onSubmit={handleSubmit} noValidate>
                            { error && (
                                <MessageBox
                                    variant="error"
                                    message={error}
                                    onClose={() => setError(null)}
                                />
                            )}

                            { success && (
                                <MessageBox
                                    variant="success"
                                    message={success}
                                    onClose={() => setSuccess(null)}
                                />
                            )}

                            <Input
                                label="Nom"
                                placeholder="Votre nom"
                                value={name}
                                onChange={(event: React.ChangeEvent<HTMLInputElement>) =>
                                    setName(event.currentTarget.value)
                                }
                                required
                                className="text-content"
                                errorText={fieldErrors.name}
                            />
                            <Input
                                label="Email"
                                placeholder="Votre email"
                                value={email}
                                onChange={(event: React.ChangeEvent<HTMLInputElement>) =>
                                    setEmail(event.currentTarget.value)
                                }
                                required
                                className="text-content"
                                errorText={fieldErrors.email}
                            />
                            <Input
                                type="textarea"
                                label="Message"
                                placeholder="Dites nous comment vous aider..."
                                value={message}
                                onChange={(event: React.ChangeEvent<HTMLInputElement>) =>
                                    setMessage(event.currentTarget.value)
                                }
                                required
                                className="text-content"
                                errorText={fieldErrors.message}
                            />
                            <Button
                                variant="primary"
                                type="submit"
                                disabled={loading}
                                onClick={() => console.log("submit")}
                                className="text-content"
                            >
                                { loading ? "Envoi..." : "Envoyer le message"}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </Section>
        </>
    )
}