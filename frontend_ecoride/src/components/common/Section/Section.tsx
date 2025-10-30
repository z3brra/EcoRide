import type { JSX } from "react"

export type SectionProps = {
    children: React.ReactNode
    className?: string
    id?: string
}

export function Section({
    children,
    className = "",
    id
}: SectionProps): JSX.Element {
    return (
        <div id={id} className={`section ${className}`}>
            {children}
        </div>
    )
}