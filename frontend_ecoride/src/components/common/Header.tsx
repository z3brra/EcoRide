import type { JSX } from "react"
import { Section } from "@components/common/Section"

export type HeaderProps = {
    level?: 1 | 2 | 3 | 4 | 5 | 6
    title: string
    titleVariant?: "headline" | "subtitle" | "bigcontent" | "content" | "small"

    description?: string
    descriptionVariant?: "subtitle" | "bigcontent" | "content" | "small"

    breakOnComma?: boolean

    animate?: boolean
    align?: "left" | "center" | "right"

    className?: string
}

export function Header({
    level = 1,
    title,
    titleVariant = "headline",
    description,
    descriptionVariant = "content",
    breakOnComma = false,
    animate = false,
    align = "left",
    className = ""
}: HeaderProps): JSX.Element {
    const HeadingTag = `h${level}` as keyof JSX.IntrinsicElements

    const formattedTitle = breakOnComma
    ? title.split(",").map((part, index, arr) => (
        <span key={index}>
            {part}
            {index < arr.length - 1 && <br/>}
        </span>
    ))
    : title

    return (
        <>
            <Section className={className}>
                <div
                    className={[
                        "header",
                        animate ? "header--animated": "",
                        `header--align-${align}`,
                    ].join(" ").trim()}
                >
                    <HeadingTag className={`header__title text-${titleVariant} text-primary`} >
                        {formattedTitle}
                    </HeadingTag>

                    { description && (
                        <p className={`header__description text-${descriptionVariant} text-silent`}>
                            {description}
                        </p>
                    )}
                </div>
            </Section>
        </>
    )
}