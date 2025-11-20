import type { JSX } from "react"

export type SectionMediaProps = {
    src: string
    alt: string
    rounded?: boolean
    shadow?: boolean
    fit?: "cover" | "contain"
    aspect?: "16/9" | "4/3" | "1/1" | "21/9" | "auto"
    className?: string
    loading?: "lazy" | "eager"
}

export function SectionMedia({
    src,
    alt,
    rounded = true,
    shadow = false,
    fit = "cover",
    aspect = "auto",
    className = "",
    loading = "lazy"
}: SectionMediaProps): JSX.Element {
    return (
        <div
            className={[
                "section-media",
                rounded ? "section-media--rounded" : "",
                shadow ? "section-media--shadow": "",
                className,
            ].filter(Boolean).join(" ")}
            style={{ aspectRatio: aspect}}
        >
            <img
                src={src}
                alt={alt}
                loading={loading}
                className={`section-media__img section-media__img--${fit}`}
            />
        </div>
    )
}