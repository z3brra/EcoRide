import { Header } from "@components/common/Header"
// import { Section } from "@components/common/Section"

export function Home () {
    return (
        <>
            <Header
                title="Partagez votre trajet, Sauvez la planète"
                description="Rejoignez la communauté de covoiturage eco-friendly. Réduisez votre empreinte carbone en économisant de l'argent sur vos trajets quotidien."
                titleVariant="headline"
                descriptionVariant="bigcontent"
                breakOnComma
                animate
                align="center"
            />
        </>
    )
}