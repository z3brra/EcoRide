import type { JSX } from "react"

import { MapPin, Calendar, Clock, Coins } from "lucide-react"

import { formatDate, formatTime, getStatusLabel } from "@utils/formatters"

import type { ReservationStatus } from "@models/status"

export type DriverDriveItemProps = {
    // uuid: string
    depart: string
    arrived: string
    departAt: string
    price: number
    participantsCount: number
    status: ReservationStatus
}

export function DriverDriveItem({
    // uuid,
    depart,
    arrived,
    departAt,
    price,
    participantsCount,
    status
}: DriverDriveItemProps): JSX.Element {
    const formattedDate = formatDate(departAt)
    const formattedTime = formatTime(departAt)
    const statusLabel = getStatusLabel(status)

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
                            <Calendar className="icon-secondary" size={16} />
                            <span className="text-content text-silent">
                                {formattedDate}
                            </span>
                        </div>

                        <div className="booking-item__detail">
                            <Clock className="icon-secondary" size={16} />
                            <span className="text-content text-silent">
                                {formattedTime}
                            </span>
                        </div>

                        <div className="booking-item__detail">
                            <Coins className="icon-secondary" size={16} />
                            <span className="text-content text-silent">
                                {price}
                            </span>
                        </div>
                    </div>

                    <div className="booking-item__detail">
                        <span className="text-small text-primary">
                            {participantsCount} passager{participantsCount > 1 ? "s" : ""}
                        </span>
                    </div>
                </div>
                <div className="booking-item__right">
                    <span className={`booking-item__status ${statusLabel.className} text-small`}>
                        {statusLabel.text}
                    </span>
                </div>
            </div>
        </div>
    )
}