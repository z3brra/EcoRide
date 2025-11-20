import type { JSX } from "react"
import { NavLinkItem } from "@components/common/Navbar/NavLinkItem"
import { PROFILE_ROUTES, PUBLIC_ROUTES, } from "@routes/paths"
import { useAuth } from "@provider/AuthContext"

export type NavLinksProps = {
    isAuthenticated?: boolean
    onItemClick?: () => void
    className?: string
}

export function NavLinks({
    isAuthenticated = false,
    onItemClick,
    className = ""
}: NavLinksProps): JSX.Element {
    const { logout } = useAuth()

    const links = [
        { to: PUBLIC_ROUTES.HOME, label: "Accueil", onClick: onItemClick },
        { to: PUBLIC_ROUTES.DRIVES.TO, label: "Trajets", onClick: onItemClick },
        { to: PUBLIC_ROUTES.CONTACT, label: "Contact", onClick: onItemClick },
        isAuthenticated
            ? { to: PROFILE_ROUTES.PROFILE, label: "Profil", onClick: onItemClick}
            : { to: PUBLIC_ROUTES.LOGIN, label: "Connexion", onClick: onItemClick }
    ]

    if (isAuthenticated) {
        links.push({ to: PUBLIC_ROUTES.HOME, label: "Déconnexion", onClick: logout})
    }

    return (
        <nav className={`navlinks ${className}`.trim()}>
            { links.map(({ to, label, onClick }) => (
                <NavLinkItem key={to} to={to} label={label} onClick={onClick} />
            ))}
        </nav>
    )
}