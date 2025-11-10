import type { JSX } from "react"
import { VehicleItem } from "./VehicleItem"
import type { VehicleItemProps } from "./VehicleItem"

export type VehicleListProps = {
    items: VehicleItemProps[]
    onEdit?: (uuid: string) => void
    onDelete?: (uuid: string) => void
}

export function VehicleList({
    items,
    onEdit,
    onDelete
}: VehicleListProps): JSX.Element {
    if (!items || items.length === 0) {
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
            { items.map((vehicle) => (
                <VehicleItem
                    key={vehicle.uuid}
                    {...vehicle}
                    onEdit={onEdit}
                    onDelete={onDelete}
                />
            ))}
        </div>
    )
}