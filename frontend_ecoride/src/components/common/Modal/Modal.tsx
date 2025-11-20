import type { JSX, ReactNode } from "react"
import { createPortal } from "react-dom"

import { X } from "lucide-react"

export type ModalProps = {
    isOpen: boolean
    onClose: () => void
    title?: string
    children: ReactNode
    width?: string
    className?: string
}

export function Modal({
    isOpen,
    onClose,
    title,
    children,
    width = "500px",
    className = "",
}: ModalProps): JSX.Element | null {
    if (!isOpen) {
        return null
    }

    return createPortal(
        <div className="modal__overlay" onClick={onClose}>
            <div
                className={`modal__container ${className}`}
                style={{ maxWidth: width }}
                onClick={(event:React.MouseEvent) => event.stopPropagation()}
            >
                <div className="modal__header">
                    { title && (
                        <h3 className="modal__title text-bigcontent text-primary">{title}</h3>
                    )}
                    <button 
                        className="modal__close"
                        onClick={onClose}
                        aria-label="Fermer la fenêtre"
                    >
                        <X size={20} />
                    </button>
                </div>

                <div className="modal__content">{children}</div>
            </div>
        </div>,
        document.body
    )
}