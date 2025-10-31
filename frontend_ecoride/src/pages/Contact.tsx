import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

import { Card } from "@components/common/Card/Card"
import { CardIcon } from "@components/common/Card/CardIcon"
import { CardContent } from "@components/common/Card/CardContent"

import { Mail, MessageSquare } from "lucide-react"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

export function Contact() {

    const isLoading = false

    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault()
        console.log("submit")
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
                    <CardIcon icon={<Mail size={30}/>}  />
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
                        <form className="card__form" onSubmit={handleSubmit}>
                            <Input
                                label="Nom"
                                placeholder="Votre nom"
                                // value={from}
                                onChange={() => console.log("nom")}
                                required
                                className="text-content"
                            />
                            <Input
                                label="Email"
                                placeholder="Votre email"
                                // value={from}
                                onChange={() => console.log("nom")}
                                required
                                className="text-content"
                            />
                            <Input
                                type="textarea"
                                label="Message"
                                placeholder="Dites nous comment vous aider..."
                                // value={from}
                                onChange={() => console.log("nom")}
                                required
                                className="text-content"
                            />
                            <Button
                                variant="primary"
                                disabled={isLoading}
                                onClick={() => console.log("submit")}
                                className="text-content"
                            >
                                { isLoading ? "Envoi..." : "Envoyer le message"}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </Section>
        </>
    )
}