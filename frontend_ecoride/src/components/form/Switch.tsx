import type { JSX } from "react"

export type SwitchProps = {
    checked: boolean
    onChange: (value: boolean) => void
}

export function Switch({
    checked,
    onChange
}: SwitchProps): JSX.Element {
    return (
        <div
            className={`switch ${checked ? "switch--on" : "switch--off"}`}
            onClick={() => onChange(!checked)}
        >
            <div className="switch__thumb"></div>
        </div>
    )
}