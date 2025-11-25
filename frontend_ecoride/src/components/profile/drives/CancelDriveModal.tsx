import type { JSX } from "react"

import { Modal } from "@components/common/Modal/Modal"
import { Button } from "@components/form/Button"
import { Ban, XCircle } from "lucide-react"

export type CancelDriveModalProps = {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    loading?: boolean
}

export function CancelDriveModal({
    isOpen,
    onClose,
    onConfirm,
    loading = false
}: CancelDriveModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Annuler le trajet"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir annuler ce trajet ?
                </p>

                <div className="modal__actions">
                    <Button
                        variant="white"
                        icon={<XCircle size={18} />}
                        onClick={onClose}
                    >
                        Retour
                    </Button>

                    <Button
                        variant="delete"
                        icon={<Ban size={18} />}
                        onClick={onConfirm}
                        disabled={loading}
                    >
                        { loading ? "Annulation..." : "Annuler"}
                    </Button>
                </div>
            </div>
        </Modal>
    )
}