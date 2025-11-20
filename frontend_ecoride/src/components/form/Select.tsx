import type { JSX } from "react"
import { useState, useRef, useEffect } from "react"
import { ChevronDown } from "lucide-react"
import { createPortal } from "react-dom"

export type SelectOption = {
    label: string
    value: string
}

export type SelectProps = {
    label?: string
    options: SelectOption[]
    value: string
    onChange: (value: string) => void
    placeholder?: string
    disabled?: boolean
    className?: string
}

export function Select({
    label,
    options,
    value,
    onChange,
    placeholder = "Sélectionner...",
    disabled = false,
    className = "",
}: SelectProps): JSX.Element {
    const [open, setOpen] = useState<boolean>(false)

    const [dropdownStyle, setDropdownStyle] = useState<React.CSSProperties>({})

    const selectRef = useRef<HTMLDivElement>(null)
    const dropdownRef = useRef<HTMLUListElement>(null)

    const selected = options.find((opt) => opt.value === value)

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                selectRef.current &&
                !selectRef.current.contains(event.target as Node) &&
                !dropdownRef.current?.contains(event.target as Node)
            ) {
                setOpen(false)
            }
        }

        document.addEventListener("mousedown", handleClickOutside)
        return () => document.removeEventListener("mousedown", handleClickOutside)
    }, [])

    useEffect(() => {
        if (open && selectRef.current) {
            const rect = selectRef.current.getBoundingClientRect()
            setDropdownStyle({
                position: "absolute",
                top: rect.bottom + window.scrollY + 4,
                left: rect.left + window.scrollX,
                width: rect.width,
                zIndex: 10000,
            })
        }
    }, [open])

    const handleSelect = (val: string) => {
        onChange(val)
        setOpen(false)
    }

    const dropdown = (
        <ul
            ref={dropdownRef}
            className="select__dropdown"
            style={dropdownStyle}
        >
            { options.map((opt) => (
                <li
                    key={opt.value}
                    className={`select__option ${opt.value === value ? "active" : ""} text-small`}
                    onClick={() => handleSelect(opt.value)}
                >
                    {opt.label}
                </li>
            ))}
        </ul>
    )

    return (
        <div className={`select ${className}`} ref={selectRef}>
            { label && (
                <label className="select__label text-small text-silent text-left">
                    {label}
                </label>
            )}
            <div
                className={`select__control ${open ? "open" : ""} ${disabled ? "disabled" : ""}`}
                tabIndex={0}
                onClick={() => !disabled && setOpen((prev) => !prev)}
            >
                <span
                    className={`select__value ${selected ? "text-primary" : "text-silent"} text-small`}
                >
                    { selected ? selected.label : placeholder }
                </span>
                <ChevronDown size={16} className={`select__icon ${open ? "rotated" : ""}`} />
            </div>

            { open && createPortal(dropdown, document.body)}
        </div>
    )
}