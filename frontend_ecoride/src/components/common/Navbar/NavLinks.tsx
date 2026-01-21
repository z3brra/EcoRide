import type { JSX } from "react"
import { NavLinkItem } from "@components/common/Navbar/NavLinkItem"
import { PROFILE_ROUTES, PUBLIC_ROUTES, } from "@routes/paths"
import { useAuth } from "@provider/AuthContext"

type NavItem = {
    label: string
    onClick?: () => void
    to?: string
    isAction?: boolean
}

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

    const links: NavItem[] = [
        { to: PUBLIC_ROUTES.HOME, label: "Accueil", onClick: onItemClick },
        { to: PUBLIC_ROUTES.DRIVES.TO, label: "Trajets", onClick: onItemClick },
        { to: PUBLIC_ROUTES.CONTACT, label: "Contact", onClick: onItemClick },
        isAuthenticated
            ? { to: PROFILE_ROUTES.PROFILE, label: "Profil", onClick: onItemClick}
            : { to: PUBLIC_ROUTES.LOGIN, label: "Connexion", onClick: onItemClick }
    ]

    if (isAuthenticated) {
        links.push({
            label: "Déconnexion",
            onClick: logout,
            isAction: true
        })
    }

    return (
        <nav className={`navlinks ${className}`.trim()}>
            { links.map(({ to, label, onClick, isAction }) => (
                <NavLinkItem
                    key={label}
                    to={to}
                    label={label}
                    onClick={onClick}
                    isAction={isAction}
                />
            ))}
        </nav>
    )
}