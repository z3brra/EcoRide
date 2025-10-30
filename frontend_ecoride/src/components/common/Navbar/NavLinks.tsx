import type { JSX } from "react"
import { NavLinkItem } from "@components/common/Navbar/NavLinkItem"
import { PUBLIC_ROUTES } from "@routes/paths"

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
    const links = [
        { to: PUBLIC_ROUTES.HOME, label: "Accueil" },
        { to: PUBLIC_ROUTES.DRIVES, label: "Trajets" },
        { to: PUBLIC_ROUTES.CONTACT, label: "Contact" },
        isAuthenticated
            ? { to: "#", label: "Profil" }
            : { to: PUBLIC_ROUTES.LOGIN, label: "Connexion" }
    ]

    if (isAuthenticated) {
        links.push({ to: PUBLIC_ROUTES.HOME, label: "Déconnexion" })
    }

    return (
        <nav className={`navlinks ${className}`.trim()}>
            { links.map(({ to, label }) => (
                <NavLinkItem key={to} to={to} label={label} onClick={onItemClick} />
            ))}
        </nav>
    )
}