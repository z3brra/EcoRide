import type { JSX } from "react"

export type CardProps = {
    children: React.ReactNode
    className?: string
    onClick?: () => void
}

export function Card({
    children,
    className = "",
    onClick
}: CardProps): JSX.Element {
    return (
        <div className={`card ${className}`} onClick={onClick}>
            {children}
        </div>
    )
}