import type { JSX } from "react"

import type { ProfileTab } from "@pages/Profile/Profile"

import type { CurrentUserResponse } from "@models/user"

import { ProfilInfosSection } from "./sections/ProfileInfosSection"
import { ProfileSecuritySection } from "./sections/ProfileSecuritySection"
import { ProfileBookingSection } from "./sections/ProfileBookingsSection"
import { ProfileVehiclesSection } from "./sections/ProfileVehiclesSection"
import { ProfileDrivesSection } from "./sections/ProfileDrivesSection"
import { ProfileReviewSection } from "./sections/ProfileReviewSection"
import { ProfilePreferencesSection } from "./sections/ProfilePreferencesSection"

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
            { isDriver && activeTab === "reviews" && <ProfileReviewSection /> }
            { isDriver && activeTab === "preferences" && <ProfilePreferencesSection /> }
        </div>
    )
}