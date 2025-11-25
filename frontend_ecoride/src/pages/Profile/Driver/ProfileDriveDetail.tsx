import type { JSX } from "react"

import { Section } from "@components/common/Section/Section"

import { ReturnLink } from "@components/common/ReturnLink"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { Button } from "@components/form/Button"

import {
    MapPin,
    Calendar,
    Coins,
    Car,
    Hash,
    Users,
    PlayCircle,
    Ban,
    PenSquare
} from "lucide-react"

import { useDriveDetail } from "@hook/drive/useDriveDetail"
import { formatDate, formatTime, formatColor, getStatusLabel } from "@utils/formatters"
import { PROFILE_ROUTES } from "@routes/paths"

export function ProfileDriveDetail(): JSX.Element {
    const { drive, loading, error } = useDriveDetail()

    if (loading) {
        return (
            <Section>
                <p className="text-content text-primary">Chargement du trajet...</p>
            </Section>
        )
    }

    if (!drive || error) {
        return (
            <Section>
                <p className="text-content text-silent">
                    Aucune donnée de trajet disponible.
                </p>
            </Section>
        )
    }

    const statusLabel = getStatusLabel(drive.status)
    const vehicleColor = formatColor(drive.vehicle.color)

    const canStart = drive.status === "open"
    const canEdit = drive.status === "open"
    const canCancel = drive.status === "open"

    return (
        <>
            <Section id="return">
                <ReturnLink to={PROFILE_ROUTES.PROFILE} />
            </Section>

            <Section id="driver-drive-detail" className="drive-detail driver-drive-detail">
                <Card className="drive-detail__card">
                    <CardContent gap={1}>
                        <div className="drive-detail__header">
                            <h3 className="drive-detail__title text-subtitle text-primary text-left">
                                Détail du trajet
                            </h3>

                            <span className={`drive-status ${statusLabel.className} text-small`}>
                                {statusLabel.text}
                            </span>
                        </div>

                        <div className="drive-detail__summary">
                            <MapPin size={40} className="drive-detail__summary-icon" />
                            <span className="text-bigcontent text-primarty">
                                {drive.depart} - {drive.arrived}
                            </span>
                        </div>

                        <div className="drive-detail__infos">
                            <div className="drive-detail__infos-col">
                                <div className="drive-detail__info-item">
                                    <Calendar size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Départ</p>
                                        <p className="text-content text-primary">
                                            {formatDate(drive.departAt)} à {formatTime(drive.departAt)}
                                        </p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Calendar size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Arrivée</p>
                                        <p className="text-content text-primary">
                                            {formatDate(drive.arrivedAt)} à {formatTime(drive.arrivedAt)}
                                        </p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Coins size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Prix par personne</p>
                                        <p className="text-content text-primary">
                                            {drive.price}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            

                            <div className="drive-detail__infos-col">
                                <div className="drive-detail__info-item">
                                    <Hash size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Référence</p>
                                        <p className="text-content text-primary">
                                            {drive.reference}
                                        </p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Car size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Véhicule</p>
                                        <p className="text-content text-primary">
                                            {vehicleColor.color} - {drive.vehicle.seats} places
                                        </p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Users size={20} className="icon-primary" />
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Participants</p>
                                        <p className="text-content text-primary">
                                            {drive.participantsCount} - {drive.availableSeats}/{drive.vehicle.seats} disponibles
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="driver-drive-detail__actions">
                            { canEdit && (
                                <Button
                                    variant="white"
                                    icon={<PenSquare size={18}/>}
                                    onClick={() => {}}
                                >
                                    Modifier le trajet
                                </Button>
                            )}
                            
                            { canStart && (
                                <Button
                                    variant="primary"
                                    icon={<PlayCircle size={18}/>}
                                    onClick={() => {}}
                                >
                                    Démarrer le trajet
                                </Button>
                            )}

                            { canCancel && (
                                <Button
                                    variant="delete"
                                    icon={<Ban size={18}/>}
                                    onClick={() => {}}
                                >
                                    Annuler le trajet
                                </Button>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </Section>

            <Section id="drive-participants">
                <Card className="drive-participants__card">
                    <CardContent gap={1}>
                        <h3 className="text-content text-primary text-left">Participants</h3>

                        <div className="drive-participants__empty">
                            <p className="text-small text-silent text-left">
                                La liste des participants sera affichée ici.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </Section>
        </>
    )
}