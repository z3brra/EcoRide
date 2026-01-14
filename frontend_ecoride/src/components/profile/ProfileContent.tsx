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
import { ProfileReviewModerationSection } from "./sections/employee/ProfileReviewModerationSection"
import { ProfileDisputeModerationSection } from "./sections/employee/ProfileDisputeModerationSection"
import { ProfileManageEmployeeSection } from "./sections/admin/employees/ProfileManageEmployeeSection"
import { ProfileManageUsersSection } from "./sections/admin/users/ProfileManageUsersSection"
import { ProfileAdminStatsSection } from "./sections/admin/stats/ProfileAdminStatsSection"

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
    const isEmployee = user.roles.includes("ROLE_EMPLOYEE")
    const isAdmin = user.roles.includes("ROLE_ADMIN")

    return (
        <div className="profile__content">
            { activeTab === "infos" && <ProfilInfosSection user={user} isDriver={isDriver} /> }
            { activeTab === "security" && <ProfileSecuritySection /> }
            { activeTab === "bookings" && <ProfileBookingSection /> }
            { isDriver && activeTab === "vehicles" && <ProfileVehiclesSection isDriver={isDriver} /> }
            { isDriver && activeTab === "drives" && <ProfileDrivesSection /> }
            { isDriver && activeTab === "reviews" && <ProfileReviewSection /> }
            { isDriver && activeTab === "preferences" && <ProfilePreferencesSection /> }
            { (isEmployee || isAdmin) && activeTab === "review_moderation" && <ProfileReviewModerationSection /> }
            { (isEmployee || isAdmin) && activeTab === "dispute_moderation" && <ProfileDisputeModerationSection/> }
            { isAdmin && activeTab === "manage_employee" && <ProfileManageEmployeeSection/> }
            { isAdmin && activeTab === "manage_users" && <ProfileManageUsersSection /> }
            { isAdmin && activeTab === "admin_stats" && <ProfileAdminStatsSection /> }
        </div>
    )
}