import type { JSX } from "react"
import { useState, useEffect } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Input } from "@components/form/Input"
import { Select } from "@components/form/Select"
import { Button } from "@components/form/Button"

import type { Vehicle } from "@models/vehicle"

import { useUpdateVehicle } from "@hook/vehicle/useUpdateVehicle"
import { Save, XCircle } from "lucide-react"

const colorOptions = [
    { label: "Noir", value: "BLACK" },
    { label: "Gris", value: "GREY" },
    { label: "Blanc", value: "WHITE" },
    { label: "Marron", value: "BROWN" },
    { label: "Rouge", value: "RED" },
    { label: "Orange", value: "ORANGE" },
    { label: "Jaune", value: "YELLOW" },
    { label: "Vert", value: "GREEN" },
    { label: "Bleu", value: "BLUE" },
    { label: "Violet", value: "PURPLE" },
    { label: "Rose", value: "PINK" },
]

export type EditVehiculeModalProps = {
    isOpen: boolean,
    onClose: () => void,
    vehicle: Vehicle | null
    onUpdated?: () => void
}

export function EditVehicleModal({
    isOpen,
    onClose,
    vehicle,
    onUpdated
}: EditVehiculeModalProps): JSX.Element {
    const [firstLicenseDate, setFirstLicenseDate] = useState<string>("")
    const [isElectric, setIsElectric] = useState<boolean>(false)
    const [color, setColor] = useState<string>("")
    const [seats, setSeats] = useState<number>(1)

    const {
        update,
        loading,
        error,
        success,
        setError,
        setSuccess,
    } = useUpdateVehicle()

    useEffect(() => {
        if (vehicle) {
            setFirstLicenseDate(vehicle.createdAt.slice(0, 10))
            setIsElectric(vehicle.isElectric)
            setColor(vehicle.color)
            setSeats(vehicle.seats)
        }
    }, [vehicle])

    const handleSubmit = async () => {
        if (!vehicle) {
            return
        }
        const payload = {
            firstLicenseDate,
            isElectric,
            color,
            seats
        }
        const result = await update(vehicle.uuid, payload)
        if (result) {
            onUpdated?.()
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
            title="Modifier le véhicule"
            width="500px"
        >
            { error && (
                <p className="text-small text-error">{error}</p>
            )}

            { success && (
                <p className="text-small text-success">{success}</p>
            )}

            <Input
                type="date"
                label="Date de première immatriculation"
                value={firstLicenseDate}
                onChange={(event: React.ChangeEvent<HTMLInputElement>) => setFirstLicenseDate(event.currentTarget.value)}
            />

            <div className="form__row">
                <label className="text-small text-primary">Type de véhicule</label>
                <Button
                    variant={isElectric ? "primary" : "white"}
                    onClick={() => setIsElectric(!isElectric)}
                >
                    {isElectric ? "Electrique" : "Thermique"}
                </Button>
            </div>

            <Select
                label="Couleur"
                value={color}
                onChange={(val) => setColor(val)}
                options={colorOptions}
            />

            <Input
                type="number"
                label="Nombre de places"
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
                    onClick={handleSubmit}
                    disabled={loading}
                >
                    {loading ? "Sauvegarde..." : "Sauvegarder"}
                </Button>
            </div>
        </Modal>
    )
}