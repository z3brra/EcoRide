import type { JSX } from "react"
import { DriverDriveItem } from "./DriverDriveItem"

export type DriverDriveListProps = {
    items: any[]
    loading: boolean
}

export function DriverDriveList({
    items,
    loading,
}: DriverDriveListProps): JSX.Element {
    if (loading) {
        return (
            <div className="booking-list__loading">
                <p className="text-content text-silent">
                    Chargement des trajets...
                </p>
            </div>
        )
    }

    if (!items || items.length === 0) {
        return (
            <div className="booking-list__empty">
                <p className="text-content text-silent">
                    Vous n'avez aucune réservation pour le moment.
                </p>
            </div>
        )
    }

    return (
        <div className="booking-list">
            { items.map((drive) => (
                <DriverDriveItem
                    key={drive.uuid}
                    { ...drive }
                />
            )) }
        </div>
    )
}