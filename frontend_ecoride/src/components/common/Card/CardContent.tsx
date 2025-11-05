import type { JSX } from "react"

type Direction = "row" | "column"
type Justify = "start" | "center" | "end" | "between" | "around" | "evenly"
type Align = "start" | "center" | "end" | "stretch"

export type CardContentProps = {
    children: React.ReactNode
    className?: string
    direction?: Direction
    justify?: Justify
    align?: Align
    gap?: string | number
}

export function CardContent({
    children,
    className = "",
    direction = "column",
    justify = "center",
    align = "stretch",
    gap,
}: CardContentProps): JSX.Element {
    const classes = [
        "card__content",
        `card__content--${direction}`,
        `card__content--justify-${justify}`,
        `card__content--align-${align}`,
        className,
    ].filter(Boolean).join(" ")

    const style = gap != null
        ? { gap: typeof gap === "number" ? `${gap}rem` : gap }
        : undefined

    return (
        <div className={classes} style={style}>
            {children}
        </div>
    )
}
