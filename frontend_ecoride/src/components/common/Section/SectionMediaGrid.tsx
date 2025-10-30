import type { JSX } from "react"

export type SectionMediaGridProps = {
    children: React.ReactNode
    className?: string
}

export function SectionMediaGrid({
    children,
    className = ""
}: SectionMediaGridProps): JSX.Element {
    return (
        <div className={`section-media-grid ${className}`}>
            {children}
        </div>
    )
}