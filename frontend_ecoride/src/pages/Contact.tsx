import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

export function Contact() {
    return (
        <>
            <Section id="contact">
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
            
        </>
    )
}