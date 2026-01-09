import type { JSX } from "react"

import {
    XCircle,
    CheckCircle2
} from "lucide-react"

import { Modal } from "@components/common/Modal/Modal"

import { Button } from "@components/form/Button"

export type ConfirmUserUnbanModalProps = {
    isOpen: boolean
    onSubmit: () => void
    onClose: () => void
    loading?: boolean
}

export function ConfirmUserUnbanModal({
    isOpen,
    onSubmit,
    onClose,
    loading
}: ConfirmUserUnbanModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Débannir l'utilisateur"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir débannir l'utilisateur ?
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
                        variant="primary"
                        icon={<CheckCircle2 size={18} />}
                        onClick={onSubmit}
                        disabled={loading}
                    >
                        { loading ? "Débanissement..." : "Débannir" }
                    </Button>
                </div>
            </div>
        </Modal>
    )
}