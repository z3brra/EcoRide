import type { JSX } from "react"
import { Car, SquarePen, Trash2 } from "lucide-react"
import { Button } from "@components/form/Button"

import { formatColor } from "@utils/formatters"

export type VehicleItemProps = {
    uuid: string
    licensePlate: string
    color: string
    seats: number
    isElectric: boolean
    onEdit?: (uuid: string) => void
    onDelete?: (uuid: string) => void
}

export function VehicleItem({
    uuid,
    licensePlate,
    color,
    seats,
    isElectric,
    onEdit,
    onDelete
}: VehicleItemProps): JSX.Element {
    const vehicleColor = formatColor(color)

    return (
        <div className="vehicle-item">
            <div className="vehicle-item__left">
                <div className="vehicle-item__icon">
                    <Car size={28} />
                </div>

                <div className="vehicle-item__infos">
                    { isElectric && (
                        <span className="vehicle-item__label text-small">
                            Electrique
                        </span>
                    )}
                    <span className="vehicle-item__plate text-content text-primary">
                        {licensePlate}
                    </span>
                    <span className="vehicle-item__color text-small text-silent">
                        {`Couleur : ${vehicleColor.color}`}
                    </span>
                    <span className="vehicle-item__seats text-small text-silent">
                        {seats} place{seats > 1 ? "s" : ""}
                    </span>
                </div>
            </div>

            <div className="vehicle-item__actions">
                <Button
                    variant="white"
                    icon={<SquarePen size={18} />}
                    onClick={() => onEdit?.(uuid)}
                    aria-label="Modifier le véhicule"
                >
                    Modifier
                </Button>
                <Button
                    variant="delete"
                    icon={<Trash2 size={18} />}
                    onClick={() => onDelete?.(uuid)}
                    aria-label="Supprimer le véhicule"
                >
                    Supprimer
                </Button>
            </div>
        </div>
    )
}