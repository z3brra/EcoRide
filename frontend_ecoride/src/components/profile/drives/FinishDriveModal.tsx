import type { JSX } from "react"

import { Modal } from "@components/common/Modal/Modal"
import { Button } from "@components/form/Button"
import { BookmarkCheck, XCircle } from "lucide-react"

export type FinishDriveModalProps = {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    loading?: boolean
}

export function FinishDriveModal({
    isOpen,
    onClose,
    onConfirm,
    loading = false
}: FinishDriveModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Terminer le trajet"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir terminer ce trajet ?
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
                        variant="primary"
                        icon={<BookmarkCheck size={18} />}
                        onClick={onConfirm}
                        disabled={loading}
                    >
                        { loading ? "Terminer..." : "Terminer"}
                    </Button>
                </div>
            </div>
        </Modal>
    )
}