import type { JSX } from "react"

export type CardProps = {
    children: React.ReactNode
    animate?: boolean
    className?: string
    onClick?: () => void
}

export function Card({
    children,
    animate = false,
    className = "",
    onClick
}: CardProps): JSX.Element {
    return (
        <div
            className={[
                "card",
                animate ? "card--animated" : "",
                className,
            ].filter(Boolean).join(" ")}
            onClick={onClick}
        >
            {children}
        </div>
    )
}