import type { JSX } from "react"
import { PassengerBookingItem } from "./PassengerBookingItem"
import type { Drive } from "@models/drive"

export type PassengerBookingListProps = {
    data: Drive[]
    loading?: boolean
    onCancel?: (uuid: string) => void
}

export function PassengerBookingList({
    data,
    loading,
    onCancel,
}: PassengerBookingListProps): JSX.Element {
    if (loading) {
        return (
            <div className="booking-list__loading">
                <p className="text-content text-silent">
                    Chargement des réservations...
                </p>
            </div>
        )
    }

    if (!data || data.length === 0) {
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
            { data.map((drive) => (
                <PassengerBookingItem
                    key={drive.uuid}
                    uuid={drive.uuid}
                    depart={drive.depart}
                    arrived={drive.arrived}
                    departAt={drive.departAt}
                    price={drive.price}
                    driver={{
                        uuid: drive.owner.uuid,
                        pseudo: drive.owner.pseudo,
                    }}
                    vehicle={{
                        color: drive.vehicle.color,
                    }}
                    status={
                        drive.status as 
                            | "open"
                            | "in_progress"
                            | "finished"
                            | "cancelled"
                    }

                    onCancel={onCancel}
                />
            ))}
        </div>
    )
}