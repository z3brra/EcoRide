import type { JSX } from "react"
import { NavLink } from "react-router-dom"

export type NavLinkItemProps = {
    to: string
    label: string
    onClick?: () => void
}

export function NavLinkItem({
    to,
    label,
    onClick
}: NavLinkItemProps): JSX.Element {
    return (
        <NavLink
            to={to}
            end={false}
            onClick={onClick}
            className={({ isActive }) => 
                `navlink ${isActive ? "active" : ""} text-content`
            }
        >
            {label}
        </NavLink>
    )
}