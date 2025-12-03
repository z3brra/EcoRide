import type { JSX } from "react"
import { useState } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Button } from "@components/form/Button"
import { Input } from "@components/form/Input"
import { Star } from "lucide-react"

export type CreateReviewModalProps = {
    isOpen: boolean
    onClose: () => void
    onSubmit: (rate: number, comment: string) => void
    loading?: boolean
}

export function CreateReviewModal({
    isOpen,
    onClose,
    onSubmit,
    loading = false
}: CreateReviewModalProps): JSX.Element {
    const [rate, setRate] = useState<number>(0)
    const [comment, setComment] = useState<string>("")

    const handleSubmit = () => {
        if (rate === 0) {
            return
        }

        onSubmit(rate, comment.trim())
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Donnez votre avis"
        >
            <div className="modal__content">
                <div className="review__stars">
                    {[1, 2, 3, 4, 5].map((n) => (
                        <Star
                            key={n}
                            size={30}
                            className={n <= rate ? "text-yellow" : "text-silent"}
                            fill="currentColor"
                            onClick={() => setRate(n)}
                            style={{ cursor: "pointer" }}
                        />
                    ))}
                </div>

                <Input
                    type="textarea"
                    label="Commentaire (facultatif)"
                    value={comment}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setComment(event.currentTarget.value)}
                />

                <div className="modal__actions">
                    <Button
                        variant="white"
                        onClick={onClose}
                        disabled={loading}
                    >
                        Plus tard
                    </Button>

                    <Button
                        variant="primary"
                        disabled={loading || rate === 0}
                        onClick={handleSubmit}
                    >
                        { loading ? "Envoi..." : "Envoyer l'avis"}
                    </Button>
                </div>
            </div>
        </Modal>
    )
}