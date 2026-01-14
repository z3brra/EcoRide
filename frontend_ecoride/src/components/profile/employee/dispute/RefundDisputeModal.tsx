import type { JSX } from "react"

import { useState, useEffect } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"
import { CheckCircle2, XCircle } from "lucide-react"

export type RefundDisputeModalProps = {
    isOpen: boolean
    onClose: () => void
    onSubmit: (comment: string) => void
    loading?: boolean
}

export function RefundDisputeModal({
    isOpen,
    onClose,
    onSubmit,
    loading = false
}: RefundDisputeModalProps): JSX.Element {
    const [comment, setComment] = useState<string>("")

    useEffect(() => {
        if (isOpen) {
            setComment("")
        }
    }, [isOpen])

    const handleSubmit = () => {
        onSubmit(comment.trim())
    }
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Rembourser le litige"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Vous allez procéder au remboursement. Vous pouvez ajouter un commentaire interne.
                </p>

                <Input
                    type="textarea"
                    label="Commentaire"
                    value={comment}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setComment(event.currentTarget.value)}
                />

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
                        onClick={handleSubmit}
                        disabled={loading}
                    >
                        { loading ? "Validation..." : "Rembourser" }
                    </Button>
                </div>
            </div>

        </Modal>
    )
}