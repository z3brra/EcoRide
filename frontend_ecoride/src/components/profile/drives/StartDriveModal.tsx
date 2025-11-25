import type { JSX } from "react"

import { Modal } from "@components/common/Modal/Modal"
import { Button } from "@components/form/Button"
import { PlayCircle, XCircle } from "lucide-react"

export type StartDriveModalProps = {
    isOpen: boolean
    onClose: () => void
    onConfirm: () => void
    loading?: boolean
}

export function StartDriveModal({
    isOpen,
    onClose,
    onConfirm,
    loading = false
}: StartDriveModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Démarrer le trajet"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir démarrer ce trajet ?
                </p>

                <div className="modal__actions">
                    <Button
                        variant="white"
                        icon={<XCircle size={18} />}
                        onClick={onClose}
                    >
                        Annuler
                    </Button>

                    <Button
                        variant="primary"
                        icon={<PlayCircle size={18} />}
                        onClick={onConfirm}
                        disabled={loading}
                    >
                        { loading ? "Démarrage..." : "Démarrer" }
                    </Button>
                </div>
            </div>
        </Modal>
    )
}