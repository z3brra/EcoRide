import type { JSX } from "react"

import { MapPin, Calendar, Clock, Coins, User, Car } from "lucide-react"
import { Button } from "@components/form/Button"

import { formatDate, formatTime, getStatusLabel, formatColor } from "@utils/formatters"


export type ReservationStatus = "open" | "in_progress" | "finished" | "cancelled"

export type PassengerBookingItemProps = {
    uuid: string
    depart: string
    arrived: string
    departAt: string
    price: number
    driver: {
        uuid: string
        pseudo: string
    }
    vehicle: {
        color: string
    }
    status: ReservationStatus
    onCancel?: (uuid: string) => void
}

export function PassengerBookingItem({
    uuid,
    depart,
    arrived,
    departAt,
    price,
    driver,
    vehicle,
    status,
    onCancel
}: PassengerBookingItemProps): JSX.Element {
    const formattedDate = formatDate(departAt)
    const formattedTime = formatTime(departAt)

    const statusLabel = getStatusLabel(status)

    const vehicleColor = formatColor(vehicle.color)

    return (
        <div className="booking-item">
            <div className="booking-item__top">
                <div className="booking-item__left">
                    <div className="booking-item__route">
                        <MapPin className="icon-primary" size={18} />
                        <span className="text-content text-primary">
                            {depart} - {arrived}
                        </span>
                    </div>

                    <div className="booking-item__details">
                        <div className="booking-item__detail">
                            <Calendar size={16} className="icon-primary" />
                            <span className="text-small text-silent">{formattedDate}</span>
                        </div>
                        <div className="booking-item__detail">
                            <Clock size={16} className="icon-primary" />
                            <span className="text-small text-silent">{formattedTime}</span>
                        </div>
                        <div className="booking-item__detail">
                            <Coins size={16} className="icon-primary" />
                            <span className="text-small text-silent">{price}</span>
                        </div>
                    </div>

                    <div className="booking-item__driver">
                        <User size={16} className="icon-primary" />
                        <span className="text-small text-primary">{driver.pseudo}</span>
                        <Car size={16} className="icon-primary" />
                        <span className="booking-item__color text-small text-silent">{vehicleColor.color}
                        </span>
                    </div>
                </div>

                <div className="booking-item__right">
                    <span className={`booking-item__status ${statusLabel.className} text-small`}>
                        {statusLabel.text}
                    </span>
                </div>
            </div>

            { status === "open" && (
                <div className="booking-item__bottom">
                    <Button
                        variant="delete"
                        className="booking-item__cancel"
                        onClick={() => onCancel?.(uuid)}
                    >
                        Annuler ma participation
                    </Button>
                </div>
            )}
        </div>
    )

}