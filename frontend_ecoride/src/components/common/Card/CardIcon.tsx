import type { JSX } from "react"

export type CardIconProps = {
    icon: JSX.Element
    className?: string
}

export function CardIcon({
    icon,
    className = ""
}: CardIconProps): JSX.Element {
    return (
        <div className={`card__icon ${className}`}>
            {icon}
        </div>
    )
}