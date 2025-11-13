import type { JSX } from "react"
import { useState } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Input } from "@components/form/Input"
import { Select } from "@components/form/Select"
import { Button } from "@components/form/Button"

import { useCreateVehicle } from "@hook/vehicle/useCreateVehicle"

import type { CreateVehicle } from "@models/vehicle"
import { vehicleColors } from "@utils/colors"
import { Save, XCircle } from "lucide-react"

export type CreateVehicleModalProps = {
    isOpen: boolean
    onClose: () => void
    onCreated?: () => void
}

export function CreateVehicleModal({
    isOpen,
    onClose,
    onCreated
}: CreateVehicleModalProps): JSX.Element {
    const [licensePlate, setLicensePlate] = useState<string>("")
    const [firstLicenseDate, setFirstLicenseDate] = useState<string>("")
    const [isElectric, setIsElectric] = useState<boolean>(false)
    const [color, setColor] = useState<string>("BLACK")
    const [seats, setSeats] = useState<number>(4)

    const {
        create,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useCreateVehicle()

    const handleSubmit = async () => {
        const payload: CreateVehicle = {
            licensePlate,
            firstLicenseDate,
            isElectric,
            color,
            seats
        }

        const result = await create(payload)
        if (result) {
            onCreated?.()
            setTimeout(() => onClose(), 300)
        }
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={() => {
                setError(null)
                setSuccess(null)
                onClose()
            }}
            title="Ajouter un véhicule"
            width="500px"
        >
            { error && (
                <p className="text-small text-error">{error}</p>
            )}

            { success && (
                <p className="text-small text-success">{success}</p>
            )}

            <Input
                label="Plaque d'immatriculation"
                placeholder="AB-123-CD"
                value={licensePlate}
                onChange={(event: React.ChangeEvent<HTMLInputElement>) => setLicensePlate(event.currentTarget.value)}
            />

            <Input
                type="date"
                label="Date de première immatriculation"
                value={firstLicenseDate}
                onChange={(event: React.ChangeEvent<HTMLInputElement>) => setFirstLicenseDate(event.currentTarget.value)}
            />

            <div className="form__row">
                <label className="text-small text-silent">Type de véhicule</label>
                <Button
                    variant={isElectric ? "primary" : "white"}
                    onClick={() => setIsElectric(!isElectric)}
                >
                    { isElectric ? "Electrique" : "Thermique" }
                </Button>
            </div>

            <Select
                label="Couelur"
                value={color}
                onChange={(val) => setColor(val)}
                options={vehicleColors}
            />

            <Input
                type="number"
                label="Nombre de place"
                value={seats}
                min={1}
                onChange={(event: React.ChangeEvent<HTMLInputElement>) => setSeats(parseInt(event.currentTarget.value, 10) || 1)}
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
                    { loading ? "Création..." : "Créer" }
                </Button>
            </div>
        </Modal>
    )
}