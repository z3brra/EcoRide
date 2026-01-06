import type { JSX } from "react"
import { useState } from "react"

import { Modal } from "@components/common/Modal/Modal"
import { Button } from "@components/form/Button"
import { Input } from "@components/form/Input"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { CheckCircle2, XCircle, AlertTriangle } from "lucide-react"

export type SettlementModalProps = {
    isOpen: boolean
    onClose: () => void
    driveUuid: string
    onConfirm: (uuid: string) => void
    // onDispute: (uuid: string, comment: string) => void
    onDispute: (comment: string) => void
    loading?: boolean
    error?: string | null
}

export function SettlementModal({
    isOpen,
    onClose,
    driveUuid,
    onConfirm,
    onDispute,
    loading = false,
    error
}: SettlementModalProps): JSX.Element {
    const [comment, setComment] = useState<string>("")


    const handleDispute = () => {
        if (!comment.trim()) {
            return
        }
        console.log(`Modal : ${comment}`)
        // onDispute(driveUuid, comment.trim())
        onDispute(comment.trim())
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Validation du trajet"
        >
            <div className="modal__content">
                { error && (
                    <MessageBox variant="error" message={error} onClose={() => {}} />
                )}

                <p className="text-content text-silent">
                    Veuillez confirmer votre dernier trajet ou ouvrir un litige en saisissant la raison dans le champ ci-dessous.
                </p>

                <Input
                    type="textarea"
                    label="Signaler un litige"
                    value={comment}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setComment(event.currentTarget.value)}
                />
            </div>

            <div className="modal__actions">
                <Button
                    variant="primary"
                    icon={<CheckCircle2 size={18} />}
                    disabled={loading}
                    onClick={() => onConfirm(driveUuid)}
                >
                    { loading ? "Validation..." : "Confirmer le trajet"}
                </Button>

                <Button
                    variant="delete"
                    icon={<AlertTriangle size={18} />}
                    disabled={loading || !comment.trim()}
                    onClick={handleDispute}
                >
                    { loading ? "Envoi..." : "Ouvrir un litige"}
                </Button>

                <Button
                    variant="white"
                    icon={<XCircle size={18} />}
                    disabled={loading}
                    onClick={onClose}
                >
                    Annuler
                </Button>
            </div>

        </Modal>
    )
}