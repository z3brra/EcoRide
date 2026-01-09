import type { JSX } from "react"

import {
    XCircle,
    Ban
} from "lucide-react"

import { Modal } from "@components/common/Modal/Modal"

import { Button } from "@components/form/Button"

export type ConfirmUserBanModalProps = {
    isOpen: boolean
    onSubmit: () => void
    onClose: () => void
    loading?: boolean
}

export function ConfirmUserBanModal({
    isOpen,
    onSubmit,
    onClose,
    loading
}: ConfirmUserBanModalProps): JSX.Element {
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Bannir l'utilisateur"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Êtes-vous sûr de vouloir bannir l'utilisateur ?
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
                        { loading ? "Banissement..." : "Bannir" }
                    </Button>
                </div>
            </div>
        </Modal>
    )
}