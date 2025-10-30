import type { JSX } from "react"

export type SectionProps = {
    children: React.ReactNode
    className?: string
}

export function Section({
    children,
    className = ""
}: SectionProps): JSX.Element {
    return (
        <div className={`section ${className}`}>
            {children}
        </div>
    )
}