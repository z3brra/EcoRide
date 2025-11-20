import type { JSX } from "react"
import { useId } from "react"

export type InputProps = {
    type?: React.HTMLInputTypeAttribute | 'textarea'
    textSize?: string
    label?: string
    labelIcon?: JSX.Element
    required?: boolean
    errorText?: string
}& (
    | React.InputHTMLAttributes<HTMLInputElement>
    | React.TextareaHTMLAttributes<HTMLTextAreaElement>
)

export function Input({
    type = 'text',
    textSize,
    label,
    labelIcon,
    required,
    errorText,
    ...props
}: InputProps): JSX.Element {
    const id = useId()

    const fieldClass = [
        "text-input-field",
        textSize ? textSize : "text-small",
    ].filter(Boolean).join(" ")

    return (
        <div className="text-input">
            { label && (
                <label
                    htmlFor={id}
                    className={`text-input-label text-content ${required ? "is-required" : ""}`}
                >
                    <span className="text-input-label__content">
                        { labelIcon && (
                            <span className="text-input-label__icon">
                                {labelIcon}
                            </span>
                        )}
                        {label}
                    </span>
                </label>
            )}

            { type === "textarea" ? (
                <textarea
                    id={id}
                    { ...(props as React.TextareaHTMLAttributes<HTMLTextAreaElement> )}
                    className={fieldClass}
                />
            ) : (
                <input
                    id={id}
                    type={type}
                    { ...(props as React.InputHTMLAttributes<HTMLInputElement> )}
                    className={fieldClass}
                />
            )}

            { errorText && (
                <div className="text-input__error text-small">
                    {errorText}
                </div>
            )}
        </div>
    )
}