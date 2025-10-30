import { Header } from "@components/common/Header"

export function Contact() {
    return (
        <>
            <Header
                title="Nous contacter"
                description="Une question ou un commentaire ? Nous serions ravis de vous lire."
                titleVariant="subtitle"
                descriptionVariant="bigcontent"
                breakOnComma
                animate
                align="center"
            />
        </>
    )
}