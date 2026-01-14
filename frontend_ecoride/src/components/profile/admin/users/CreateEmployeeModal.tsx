import type { JSX } from "react"
import { useState, useEffect } from "react"

import {
    XCircle,
    CheckCircle2,
    Copy,
    ClipboardCheck
} from "lucide-react"

import { Modal } from "@components/common/Modal/Modal"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

export type CreateEmployeeModalProps = {
    isOpen: boolean
    onSubmit: (pseudo: string) => void
    onClose: () => void
    loading?: boolean
    credentials?: { email: string; plainPassword: string } | null
}

export function CreateEmployeeModal({
    isOpen,
    onSubmit,
    onClose,
    loading = false,
    credentials = null
}: CreateEmployeeModalProps): JSX.Element {
    const [pseudo, setPseudo] = useState<string>("")
    const [clipborded, setClipboarded] = useState<boolean>(false)

    useEffect(() => {
        if (isOpen) {
            setPseudo("")
            setClipboarded(false)
        }
    }, [isOpen])

    const trimmed = pseudo.trim()

    const handleSubmit = () => {
        if (!trimmed) {
            return
        }
        onSubmit(pseudo.trim())
    }

    const handleCopy = async () => {
        if (!credentials) {
            return
        }
        const text = `Email : ${credentials.email}\nMot de passe : ${credentials.plainPassword}`
        try {
            await navigator.clipboard.writeText(text)
            setClipboarded(true)
        } catch {}
    }

    if (credentials) {
        return (
            <Modal
                isOpen={isOpen}
                onClose={onClose}
                title="Identifiants de l'employé"
            >
                <div className="modal__content">
                    <p className="text-content text-silent">
                        Copiez ces informations maintenant. Elles ne seront plus affichés après fermeture. L'employé pourra changer son mot de passe a tout moment (recommandé).
                    </p>

                    <Input
                        type="text"
                        label="Email"
                        value={credentials.email}
                        onChange={() => {}}
                        disabled
                    />

                    <Input
                        type="text"
                        label="Mot de passe"
                        value={credentials.plainPassword}
                        onChange={() => {}}
                        disabled
                    />

                    <div className="modal__actions">
                        <Button
                            variant={clipborded ? "white" : "secondary"}
                            icon={clipborded ? <ClipboardCheck size={18} /> : <Copy size={18} />}
                            onClick={handleCopy}
                        >
                            {clipborded ? "Copié" : "Copier" }
                        </Button>

                        <Button
                            variant="primary"
                            icon={<CheckCircle2 size={18} />}
                            onClick={onClose}
                        >
                            Fermer
                        </Button>
                    </div>
                </div>
            </Modal>
        )
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Création d'un employé"
        >
            <div className="modal__content">
                <p className="text-content text-silent">
                    Afin de créer un employé, vous devez saisir son pseudo
                </p>

                <Input
                    type="text"
                    label="Pseudo de l'employé"
                    value={pseudo}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPseudo(event.currentTarget.value)}
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
                        disabled={loading || pseudo.trim().length === 0}
                    >
                        { loading ? "Validation..." : "Valider" }
                    </Button>
                </div>
            </div>
        </Modal>
    )
}