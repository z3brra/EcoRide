import type { JSX } from "react"
import { VehicleItem } from "./VehicleItem"

import type { Vehicle } from "@models/vehicle"

export type VehicleListProps = {
    data: Vehicle[]
    loading: boolean
    onEdit?: (uuid: string) => void
    onDelete?: (uuid: string) => void
}

export function VehicleList({
    data,
    loading,
    onEdit,
    onDelete
}: VehicleListProps): JSX.Element {
    if (loading) {
        return (
            <div className="vehicle-list__loading">
                <p className="text-content text-silent">
                    Chargement des véhicules...
                </p>
            </div>
        )
    }


    if (!data || data.length === 0) {
        return (
            <div className="vehicle-list__empty">
                <p className="text-content text-silent">
                    Vous n'avez encore aucun véhicule d'enregistré.
                </p>
            </div>
        )
    }

    return (
        <div className="vehicle-list">
            { data.map((vehicle) => (
                <VehicleItem
                    key={vehicle.uuid}
                    uuid={vehicle.uuid}
                    licensePlate={vehicle.licensePlate}
                    color={vehicle.color}
                    seats={vehicle.seats}
                    isElectric={vehicle.isElectric}
                    onEdit={onEdit}
                    onDelete={onDelete}
                />
            ))}
        </div>
    )
}