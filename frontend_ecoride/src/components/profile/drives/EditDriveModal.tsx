import type { JSX } from "react"
import { useState } from "react"

import { Modal } from "@components/common/Modal/Modal"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import type { Drive } from "@models/drive"
import { Save, XCircle } from "lucide-react"

export type EditDriveModalProps = {
    drive: Drive
    isOpen: boolean
    onClose: () => void
    onSubmit: (data: { departAt: string; arrivedAt: string }) => void
    loading?: boolean
}

export function EditDriveModal({
    drive,
    isOpen,
    onClose,
    onSubmit,
    loading = false
}: EditDriveModalProps): JSX.Element {
    const [departAt, setDepartAt] = useState<string>(drive.departAt.slice(0, 16))
    const [arrivedAt, setArrivedAt] = useState<string>(drive.arrivedAt.slice(0, 16))

    const handleSubmit = () => {
        onSubmit({ departAt, arrivedAt })
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Modifier le trajet"
        >
            <div className="modal__content">
                <Input
                    type="datetime-local"
                    label="Date de départ"
                    value={departAt}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setDepartAt(event.currentTarget.value)}
                />

                <Input
                    type="datetime-local"
                    label="Date d'arrivée"
                    value={arrivedAt}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setArrivedAt(event.currentTarget.value)}
                />

                <div className="modal__actions">
                    <Button
                        variant="white"
                        icon={<XCircle size={20} />}
                        onClick={onClose}
                    >
                        Annuler
                    </Button>

                    <Button
                        variant="primary"
                        icon={<Save size={20} />}
                        disabled={loading}
                        onClick={handleSubmit}
                    >
                        { loading ? "Modification..." : "Sauvegarder"}
                    </Button>
                </div>
            </div>
        </Modal>
    )
}