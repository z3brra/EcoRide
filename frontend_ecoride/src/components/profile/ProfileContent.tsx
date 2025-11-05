import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { Star } from "lucide-react"

import type { CurrentUserResponse } from "@provider/AuthContext"
import type { ProfileTab } from "@pages/Profile/Profile"

export type ProfileContentProps = {
    user: CurrentUserResponse
    activeTab: ProfileTab
    isDriver: boolean
}

export function ProfileContent({
    user,
    activeTab,
    isDriver
}: ProfileContentProps): JSX.Element {
    return (
        <div className="profile__content">
            { activeTab === "infos" && (
                <Card className="profile__section">
                    <CardContent gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Informations personnelles
                        </h3>
                        <p className="text-small text-silent text-left">
                            Mettez à jour les informations relatives à votre compte.
                        </p>

                        <Input label="Pseudo" defaultValue={user.pseudo} />
                        <div className="profile__actions">
                            { !isDriver && (
                                <Button
                                    variant="secondary"
                                    onClick={() => {}}
                                >
                                    Devenir chauffeur
                                </Button>
                            )}
                            <Button
                                variant="primary"
                                onClick={() => {}}
                            >
                                Sauvegarder
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            )}

            { activeTab === "security" && (
                <Card className="profile__section">
                    <CardContent  gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Modifier le mot de passe
                        </h3>
                        <p className="text-small text-silent text-left">
                            Mettez à jour votre mot de passe pour sécuriser votre compte
                        </p>

                        <Input type="password" label="Mot de passe actuel" />
                        <Input type="password" label="Nouveau mot de passe" />
                        <Input type="password" label="Confirmer le mot de passe" />
                        <div className="profile__actions">
                            <Button
                                variant="primary"
                                onClick={() => {}}
                            >
                                Sauvegarder
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            )}

            { activeTab === "bookings" && (
                <Card className="profile__section">
                    <CardContent  gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Mes réservations
                        </h3>
                        <p className="text-small text-silent text-left">
                            Voyages auxquels vous avez participé en tant que passager.
                        </p>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "vehicles" && (
                <Card className="profile__section">
                    <CardContent direction="row" justify="between" align="center" gap={1}>
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Mes véhicules
                            </h3>
                            <p className="text-small text-silent text-left">
                                Gérez vos véhicules enregistrés.
                            </p>
                        </div>
                        <Button
                            variant="primary"
                            onClick={() => {}}
                        >
                            Ajouter un véhicule
                        </Button>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "drives" && (
                <Card className="profile__section">
                    <CardContent direction="row" justify="between" align="center" gap={1}>
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Mes trajets
                            </h3>
                            <p className="text-small text-silent text-left">
                                Gérez vos trajets en covoiturage.
                            </p>
                        </div>
                        <Button
                            variant="primary"
                            onClick={() => {}}
                        >
                            Créer un trajet
                        </Button>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "reviews" && (
                <Card className="profile__section">
                    <CardContent gap={1}>
                        <div className="profile__reviews-header">
                            <div>
                                <h3 className="text-subtitle text-primary text-left">
                                    Mes avis
                                </h3>
                                <p className="text-small text-silent text-left">
                                    Commentaires de vos passagers.
                                </p>
                            </div>
                            <div className="profile__reviews-score">
                                <Star fill="currentColor" className="text-yellow" />
                                <span className="text-bigcontent text-primary">4.6</span>
                                <p className="text-small text-silent">5 avis</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "preferences" && (
                <div className="profile__preferences">
                    <Card>
                        <CardContent gap={1}>
                            <h3 className="text-subtitle text-primary text-left">
                                Préférences fixes
                            </h3>
                            <p className="text-small text-silent text-left">
                                Définissez vos préférences de conduite standard.
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent gap={1}>
                            <h3 className="text-subtitle text-primary text-left">
                                Préférences personalisées
                            </h3>
                            <p className="text-small text-silent text-left">
                                Ajoutez vos préférences de trajet.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            )}
        </div>
    )
}