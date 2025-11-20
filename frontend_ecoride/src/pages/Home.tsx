import { useNavigate } from "react-router-dom"

import { Card } from "@components/common/Card/Card"
import { CardGrid } from "@components/common/Card/CardGrid"
import { CardIcon } from "@components/common/Card/CardIcon"
import { CardContent } from "@components/common/Card/CardContent"

import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"
import { SectionMedia } from "@components/common/Section/SectionMedia"
import { SectionMediaGrid } from "@components/common/Section/SectionMediaGrid"

import { DriveSearchCard } from "@components/drive/DriveSearchCard"

import { Leaf, TrendingDown, Users, Car } from "lucide-react"

import carpool from "@assets/carpool.jpg"
import incar from "@assets/incar_trees.jpg"
import tesla from "@assets/tesla_in_forest.jpg"
import { PUBLIC_ROUTES } from "@routes/paths"

export function Home () {
    const navigate = useNavigate()

    const handleSearch = (data: {
        from: string;
        to: string;
        date: string
    }) => {
        const params = new URLSearchParams({
            from: data.from,
            to: data.to,
            date: data.date,
            page: "1"
        })

        navigate(`${PUBLIC_ROUTES.DRIVES.TO}?${params.toString()}`)
    }

    return (
        <>
            <Section id="header">
                <SectionHeader
                    title="Partagez votre trajet, Sauvez la planète"
                    description="Rejoignez la communauté de covoiturage eco-friendly. Réduisez votre empreinte carbone en économisant de l'argent sur vos trajets quotidien."
                    titleVariant="headline"
                    descriptionVariant="bigcontent"
                    breakOnComma
                    animate
                    align="center"
                />

                <DriveSearchCard onSearch={handleSearch} />
            </Section>

            <Section id="about">
                <SectionHeader
                    title="A propos d'Ecoride"
                    description="
                    Ecoride est une plateforme de covoiturage durable conçue pour mettre en relation les personnes voyageant dans la même direction.
                    Notre mission est de réduire les embouteillages, de diminuer les émissions de carbone et de rendre les transports plus abordables et plus sociaux.
                    Que vous vous rendiez au travail, à l'école ou que vous planifiez un voyage le week-end, Ecoride vous aide à trouver le compagnon de voyage idéal.
                    "
                    titleVariant="subtitle"
                    descriptionVariant="content"
                    animate
                    align="center"
                />
            </Section>

            <Section id="features">
                <SectionHeader
                    title="Pourquoi choisir Ecoride ?"
                    titleVariant="subtitle"
                    animate
                    align="center"
                />
                <CardGrid>
                    <Card animate>
                        <CardIcon icon={<Leaf size={30}/>} />
                        <CardContent>
                            <h3 className="card__title">
                                Eco-Friendly
                            </h3>
                            <p className="card__description">
                                Réduisez votre empreinte carbone avec le covoiturage et contribuez pour un environnement sain
                            </p>
                        </CardContent>
                    </Card>

                    <Card animate>
                        <CardIcon icon={<TrendingDown size={30}/>} />
                        <CardContent>
                            <h3 className="card__title">
                                Faites des économies
                            </h3>
                            <p className="card__description">
                                Répartissez les coûts de carburant et réduisez considérablement vos frais de transport.
                            </p>
                        </CardContent>
                    </Card>

                    <Card animate>
                        <CardIcon icon={<Users size={30}/>} />
                        <CardContent>
                            <h3 className="card__title">
                                Faites des rencontres
                            </h3>
                            <p className="card__description">
                                Entrez en contact avec des voyageurs qui partagent vos centres d'intérêt et rendez vos trajets quotidiens plus agréables.
                            </p>
                        </CardContent>
                    </Card>

                    <Card animate>
                        <CardIcon icon={<Car size={30}/>} />
                        <CardContent>
                            <h3 className="card__title">
                                Facile à utiliser
                            </h3>
                            <p className="card__description">
                                Processus de recherche et de réservation simple pour trouver des trajets qui correspondent à votre emploi du temps.
                            </p>
                        </CardContent>
                    </Card>
                </CardGrid>
            </Section>

            <Section id="community">
                <SectionHeader
                    title="Rejoignez notre communauté"
                    titleVariant="subtitle"
                    animate
                    align="center"
                />

                <SectionMediaGrid>
                    <SectionMedia
                        src={carpool}
                        alt="Conducteur partageant un trajet"
                        aspect="4/3"
                    />

                    <SectionMedia
                        src={incar}
                        alt="Vue intérieur d'une voiture dans la nature"
                        aspect="4/3"
                    />

                    <SectionMedia
                        src={tesla}
                        alt="Une voiture electrique dans la nature"
                        aspect="4/3"
                    />
                </SectionMediaGrid>
            </Section>

        </>
    )
}