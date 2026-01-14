export function getRoleTag(role: string) {
    switch (role) {
        case "ROLE_DRIVER":
            return { label: "Chauffeur", className: "admin-user-item__role-tag--driver" }
        case "ROLE_EMPLOYEE":
            return { label: "Employé", className: "admin-user-item__role-tag--employee" }
        case "ROLE_ADMIN":
            return { label: "Admin", className: "admin-user-item__role-tag--admin" }
        default:
            return null
    }
}