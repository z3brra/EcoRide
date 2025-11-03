import type { JSX } from "react"

type Direction = "row" | "column"
type Justify = "start" | "center" | "end" | "between" | "around" | "evenly"

export type CardContentProps = {
    children: React.ReactNode
    className?: string
    direction?: Direction
    justify?: Justify
    gap?: string | number
}

export function CardContent({
    children,
    className = "",
    direction = "column",
    justify = "center",
    gap,
}: CardContentProps): JSX.Element {
    const classes = [
        "card__content",
        `card__content--${direction}`,
        `card__content--${justify}`,
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
