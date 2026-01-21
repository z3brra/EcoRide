import type { JSX } from "react"
import { NavLink } from "react-router-dom"

export type NavLinkItemProps = {
    to?: string
    label: string
    onClick?: () => void
    isAction?: boolean
}

export function NavLinkItem({
    to,
    label,
    onClick,
    isAction = false
}: NavLinkItemProps): JSX.Element {
    if (isAction) {
        return (
            <button
                type="button"
                className="navlink navlink--action text-content"
                onClick={onClick}
            >
                {label}
            </button>
        )
    }

    return (
        <NavLink
            to={to!}
            end
            onClick={onClick}
            className={({ isActive }) => 
                `navlink ${isActive ? "active" : ""} text-content`
            }
        >
            {label}
        </NavLink>
    )
}