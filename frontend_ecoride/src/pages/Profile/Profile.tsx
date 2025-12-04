import { useState } from "react"
import { useAuth } from "@provider/AuthContext"

import { Section } from "@components/common/Section/Section"

import { ProfileSidebar } from "@components/profile/ProfileSidebar"
import { ProfileContent } from "@components/profile/ProfileContent"

export type ProfileTab = 
    | "infos"
    | "security"
    | "bookings"
    | "vehicles"
    | "drives"
    | "reviews"
    | "preferences"
    | "review_moderation"

export function Profile() {
    const { user } = useAuth()
    const [activeTab, setActiveTab] = useState<ProfileTab>("infos")

    if (!user) {
        return (
            <Section>
                <p className="text-content text-silent">Veuillez vous connecter pour accéder à votre profile</p>
            </Section>
        )
    }

    const isDriver = user.roles.includes("ROLE_DRIVER")

    return (
        <Section id="profile" className="profile">
            <div className="profile__layout">
                <ProfileSidebar
                    user={user}
                    activeTab={activeTab}
                    setActiveTab={setActiveTab}
                    isDriver={isDriver}
                />

                <ProfileContent
                    user={user}
                    activeTab={activeTab}
                    isDriver={isDriver}
                />
            </div>
        </Section>
    )
}