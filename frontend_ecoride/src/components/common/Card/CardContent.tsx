import type { JSX } from "react"

export type CardContentProps = {
    children: React.ReactNode
    className?: string
}

export function CardContent({
    children,
    className = ""
}: CardContentProps): JSX.Element {
    return (
        <div className={`card__content ${className}`}>
            {children}
        </div>
    )
}
