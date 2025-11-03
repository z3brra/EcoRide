import { Link } from "react-router-dom"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import {
    User,
    MapPin,
    Calendar,
    Coins
} from "lucide-react"

import { formatDate, formatTime } from "@utils/formatters"
import { Button } from "@components/form/Button"

import type { Drive } from "@models/drive"


export type DriveItemProps = {
    item: Drive
}

export function DriveItem({
    item
}: DriveItemProps) {
    const {
        uuid,
        owner,
        vehicle,
        availableSeats,
        price,
        depart,
        departAt,
        arrived
    } = item

    const formmattedDate = formatDate(departAt)
    const formattedHour = formatTime(departAt)

    return (
        <Link key={uuid} to={uuid} className="drive-item">
            <Card className="drive-item__card">
                <CardContent className="drive-item__left">
                    <div className="drive-item__row">
                        <User size={18} className="drive-item__icon" />
                        <span className="text-content text-primary">{owner.pseudo}</span>
                        { vehicle.isElectric && (
                            <span className="drive-item__tag text-small">Electrique</span>
                        )}
                    </div>

                    <div className="drive-item__row">
                        <MapPin size={18} className="drive-item__icon" />
                        <span className="text-small text-silent">{depart} - {arrived}</span>
                    </div>

                    <div className="drive-item__row">
                        <Calendar size={18} className="drive-item__icon" />
                        <span className="text-small text-silent">{formmattedDate} à {formattedHour}</span>
                    </div>
                </CardContent>

                <CardContent className="drive-item__right" direction="row">
                    <div className="drive-item__info">
                        <div className="drive-item__info-top">
                            <Coins size={18} className="drive-item__icon" />
                            <span className="text-bigcontent text-primary">{price}</span>
                        </div>
                        <span className="drive-item__info-bottom text-small text-silent">par personne</span>
                    </div>

                    <div className="drive-item__info">
                        <span className="text-bigcontent text-primary">{availableSeats}</span>
                        <span className="drive-item__info-bottom text-small text-silent">places restantes</span>
                    </div>

                    <Button
                        variant="primary"
                        onClick={(e) => {
                            e.preventDefault()
                            e.stopPropagation()
                        }}
                    >
                        Réserver
                    </Button>
                </CardContent>
            </Card>
        </Link>
    )

    // return (
    //     <Card key={uuid} className="drive-item">
    //         <Link
    //             key={uuid}
    //             to={`${uuid}`}
    //             className="drive-item__link"
    //         >

    //             <CardContent className="drive-item__left">
    //                 <div className="drive-item__row">
    //                     <User size={18} className="drive-item__icon" />
    //                     <span className="text-content text-primary">{owner.pseudo}</span>
    //                     { vehicle.isElectric && (
    //                         <span className="drive-item__tag text-small">Electrique</span>
    //                     )}
    //                 </div>
                    
    //                 <div className="drive-item__row">
    //                     <MapPin size={18} className="drive-item__icon" />
    //                     <span className="text-small text-silent">
    //                         {depart} - {arrived}
    //                     </span>
    //                 </div>

    //                 <div className="drive-item__row">
    //                     <Calendar size={18} className="drive-item__icon" />
    //                     <span className="text-small text-silent">
    //                         {formmattedDate} à {formattedHour}
    //                     </span>
    //                 </div>
    //             </CardContent>

    //             <CardContent className="drive-item__right">
    //                 <div className="drive-item__info">
    //                     <div className="drive-item__info-top">
    //                         <Coins size={18} className="drive-item__icon" />
    //                         <span className="text-bigcontent text-primary">{price}</span>
    //                     </div>
    //                     <span className="drive-item__info-bottom text-small text-silent">
    //                         par personne
    //                     </span>
    //                 </div>

    //                 <div className="drive-item__info">
    //                     <span className="text-bigcontent text-primary">
    //                         {availableSeats}
    //                     </span>
    //                     <span className="drive-item__info-bottom text-small text-silent">
    //                         places restantes
    //                     </span>
    //                 </div>

    //                 <Button
    //                     variant="primary"
    //                     onClick={() => {}}
    //                 >
    //                     Réserver
    //                 </Button>
    //             </CardContent>
    //         </Link>
            
    //     </Card>
    // )
}