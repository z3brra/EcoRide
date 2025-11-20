import type { JSX } from "react"

export type CardGridProps = {
    children: React.ReactNode
    className?: string
}

export function CardGrid({
    children,
    className = ""
}: CardGridProps): JSX.Element {
    return (
        <div className={`card-grid ${className}`}>
            {children}
        </div>
    )
}
