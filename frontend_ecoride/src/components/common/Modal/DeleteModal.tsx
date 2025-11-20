import type { JSX } from "react"
import { Modal } from "./Modal"
import { Button } from "@components/form/Button"

import { XCircle, Trash2 } from "lucide-react"

export type DeleteModalProps = {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    title: string
    description: string
    confirmLabel?: string
    cancelLabel?: string
    loading?: boolean
}

export function DeleteModal({
    isOpen,
    onClose,
    onConfirm,
    title,
    description,
    confirmLabel = "Supprimer",
    cancelLabel = "Annuler",
    loading = false,
}: DeleteModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title={title}
            width="420px"
        >
            <p className="text-content text-silent">{description}</p>

            <div className="modal__actions">
                <Button
                    variant="white"
                    icon={<XCircle size={20}/>}
                    onClick={onClose}
                >
                    {cancelLabel}
                </Button>
                <Button
                    variant="delete"
                    icon={<Trash2 size={20} />}
                    onClick={onConfirm}
                    disabled={loading}
                >
                    {loading ? "Suppression..." : confirmLabel }
                </Button>
            </div>
        </Modal>
    )
}