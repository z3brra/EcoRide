import type { JSX } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Button } from "@components/form/Button"
import { Ban, XCircle } from "lucide-react"

export type RefuseDisputeModalProps = {
    isOpen: boolean
    onClose: () => void
    onSubmit: () => void
    loading?: boolean
}

export function RefuseDisputeModal({
    isOpen,
    onClose,
    onSubmit,
    loading = false
}: RefuseDisputeModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Refuser le litige"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir refuser ce litige ?
                </p>
                <div className="modal__actions">
                    <Button
                        variant="white"
                        icon={<XCircle size={18} />}
                        onClick={onClose}
                        disabled={loading}
                    >
                        Annuler
                    </Button>

                    <Button
                        variant="delete"
                        icon={<Ban size={18} />}
                        onClick={onSubmit}
                        disabled={loading}
                    >
                        { loading ? "Refus..." : "Refuser" }
                    </Button>
                </div>
            </div>
        </Modal>
    )
}