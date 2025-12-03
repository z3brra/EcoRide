import type { JSX } from "react"
import { useState } from "react"

import { Modal } from "@components/common/Modal/Modal"

import { Input } from "@components/form/Input"
import { Select } from "@components/form/Select"
import { Button } from "@components/form/Button"

import type { Vehicle } from "@models/vehicle"

export type CreateDriveModalProps = {
    isOpen: boolean
    onClose: () => void
    onSubmit?: (data: {
        vehicleUuid: string
        price: number
        distance: number
        depart: string
        departAt: string
        arrived: string
        arrivedAt: string
    }) => void

    vehicles: Vehicle[]
    loading?: boolean
}

export function CreateDriveModal({
    isOpen,
    onClose,
    onSubmit,
    vehicles,
    loading
}: CreateDriveModalProps): JSX.Element {
    const [vehicleUuid, setVehicleUuid] = useState<string>("")
    const [price, setPrice] = useState<string>("")
    const [distance, setDistance] = useState<string>("")
    const [depart, setDepart] = useState<string>("")
    const [departAt, setDepartAt] = useState<string>("")
    const [arrived, setArrived] = useState<string>("")
    const [arrivedAt, setArrivedAt] = useState<string>("")

    const handleSubmit = async () => {
        if (!onSubmit) {
            return
        }

        onSubmit({
            vehicleUuid,
            price: Number(price),
            distance: Number(distance),
            depart,
            departAt,
            arrived,
            arrivedAt
        })
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            title="Créer un trajet"
        >
            {/* <div className="create-drive-modal__content"> */}
                <Select
                    label="Véhicule"
                    value={vehicleUuid}
                    onChange={(val) => setVehicleUuid(val)}
                    options={vehicles.map((vehicle) => ({
                        label: `${vehicle.licensePlate} - ${vehicle.color}`,
                        value: vehicle.uuid
                    }))}
                    disabled={loading}
                />

                <Input
                    type="number"
                    label="Prix"
                    placeholder="Ex : 10"
                    value={price}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPrice(event.currentTarget.value)}
                    disabled={loading}
                />

                <Input
                    type="number"
                    label="Distance (km)"
                    placeholder="Ex : 50"
                    value={distance}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setDistance(event.currentTarget.value)}
                    disabled={loading}
                />

                <Input
                    label="Lieu de départ"
                    placeholder="Ex : Paris"
                    value={depart}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setDepart(event.currentTarget.value)}
                    disabled={loading}
                />

                <Input
                    type="datetime-local"
                    label="Date et heure de départ"
                    value={departAt}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setDepartAt(event.currentTarget.value)}
                    disabled={loading}
                />

                <Input
                    label="Lieu d'arrivée"
                    placeholder="Ex : Lyon"
                    value={arrived}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setArrived(event.currentTarget.value)}
                    disabled={loading}
                />

                <Input
                    type="datetime-local"
                    label="Date et heure d'arrivée"
                    value={arrivedAt}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setArrivedAt(event.currentTarget.value)}
                    disabled={loading}
                />

                <div className="modal__actions">
                    <Button
                        variant="white"
                        onClick={onClose}
                        disabled={loading}
                    >
                        Annuler
                    </Button>

                    <Button
                        variant="primary"
                        onClick={handleSubmit}
                        disabled={loading}
                    >
                        Créer le trajet
                    </Button>
                </div>
            {/* </div> */}
        </Modal>
    )
}