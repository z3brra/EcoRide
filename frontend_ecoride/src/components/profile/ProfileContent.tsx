import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Star } from "lucide-react"

import type { ProfileTab } from "@pages/Profile/Profile"

import type { CurrentUserResponse } from "@models/user"

import { ProfilInfosSection } from "./sections/ProfileInfosSection"
import { ProfileSecuritySection } from "./sections/ProfileSecuritySection"
import { ProfileBookingSection } from "./sections/ProfileBookingsSection"
import { ProfileVehiclesSection } from "./sections/ProfileVehiclesSection"
import { ProfileDrivesSection } from "./sections/ProfileDrivesSection"

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
            { activeTab === "infos" && <ProfilInfosSection user={user} isDriver={isDriver} /> }
            { activeTab === "security" && <ProfileSecuritySection /> }
            { activeTab === "bookings" && <ProfileBookingSection /> }
            { isDriver && activeTab === "vehicles" && <ProfileVehiclesSection isDriver={isDriver} /> }
            { isDriver && activeTab === "drives" && <ProfileDrivesSection /> }

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