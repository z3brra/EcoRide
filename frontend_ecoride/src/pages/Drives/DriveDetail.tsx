import { Section } from "@components/common/Section/Section"
import { ReturnLink } from "@components/common/ReturnLink"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { 
    MapPin,
    Calendar,
    Coins,
    Car,
    Hash,
    Users,
    User,
    Star
} from "lucide-react"

import { useDriveDetail } from "@hook/drive/useDriveDetail"
import { useJoinDrive } from "@hook/drive/useJoinDrive"

import { formatDate, formatTime } from "@utils/formatters"

export function DriveDetail() {
    const {
        drive,
        loading,
        error,
    } = useDriveDetail()

    const {
        join,
        loading: joinLoading,
        error: joinError,
        setError: setJoinError,
        success: joinSuccess,
        setSuccess: setJoinSucces,
    } = useJoinDrive()

    const reviews = [
        {
            pseudo: "John Doe",
            rating: 5,
            comment: "Très bon conducteur, ponctuel et sympatique !"
        },
        {
            pseudo: "Jane Smith",
            rating: 3,
            comment: "Trajet agréable mais véhicule un peu étroit."
        },
        {
            pseudo: "Bob Dylan",
            rating: 2,
            comment: "Le conducteur était en retard."
        },
    ]

    if (loading) {
        return (
            <Section>
                <p className="text-content text-primary">Chargement des Informations...</p>
            </Section>
        )
    }

    if (!drive || error) {
        return (
            <Section>
                <p className="text-content text-silent">Aucune donnée de trajet disponible.</p>
            </Section>
        )
    }

    return (
        <>
            <Section id="return">
                <ReturnLink />
            </Section>

            <Section id="drive-detail" className="drive-detail">
                <Card className="drive-detail__card">

                    { joinError && (
                        <MessageBox
                            variant="error"
                            message={joinError}
                            onClose={() => setJoinError(null)}
                        />
                    )}
                    { joinSuccess && (
                        <MessageBox
                            variant="success"
                            message={joinSuccess}
                            onClose={() => setJoinSucces(null)}
                        />
                    )}

                    <CardContent>
                        <h3 className="drive-detail__title text-subtitle text-primary text-left">Détail du trajet</h3>

                        <div className="drive-detail__summary">
                            <MapPin size={40} className="drive-detail__summary-icon" />
                            <span className="text-bigcontent text-primary">
                                {drive.depart} - {drive.arrived}
                            </span>
                        </div>

                        <div className="drive-detail__infos">
                            <div className="drive-detail__infos-col">
                                <div className="drive-detail__info-item">
                                    <Calendar size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Date</p>
                                        <p className="text-content text-bold text-primary">{formatDate(drive.departAt)} à {formatTime(drive.departAt)}</p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Coins size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Prix par personne</p>
                                        <p className="text-bigcontent text-bold text-primary">{drive.price}</p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Car size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Couleur</p>
                                        <p className="text-content text-bold text-primary">{drive.vehicle.color}</p>
                                    </div>
                                </div>
                            </div>

                            <div className="drive-detail__infos-col">
                                <div className="drive-detail__info-item">
                                    <Hash size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Référence</p>
                                        <p className="text-content text-bold text-primary">{drive.reference}</p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Users size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Places restantes</p>
                                        <p className="text-bigcontent text-bold text-primary">{drive.availableSeats} / {drive.vehicle.seats}</p>
                                    </div>
                                </div>

                                <div className="drive-detail__info-item">
                                    <Users size={20} className="icon-primary"/>
                                    <div className="drive-detail__info-text">
                                        <p className="text-small text-silent">Participants</p>
                                        <p className="text-content text-bold text-primary">{drive.participantsCount}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Button
                            variant="primary"
                            className="drive-detail__button"
                            disabled={joinLoading}
                            onClick={() => join(drive.uuid, drive.owner.uuid)}
                        >
                            { joinLoading ? "Réservation..." : "Réserver" }
                        </Button>
                    </CardContent>
                </Card>
            </Section>

            <Section id="driver-info">
                <Card className="driver-detail__card">
                    <CardContent direction="row" gap={1}>
                        <User size={50} className="driver-detail__icon" />
                        <div className="drive-detail__user">
                            <h4 className="text-content text-primary">
                                {drive.owner.pseudo}
                            </h4>
                            <div className="driver-detail__rating">
                                <Star className="text-yellow" fill="currentColor" />
                                <span className="text-content text-primary">4.8</span>
                                <span className="text-small text-silent">(3 avis)</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </Section>

            <Section id="driver-reviews" className="driver-reviews">
                <Card className="driver-reviews__card">
                    <CardContent>
                        <h3 className="driver-reviews__title text-content text-primary text-left">Avis</h3>

                        <div className="driver-reviews__list">
                            {reviews.map((review, index) => (
                                <div key={index} className="driver-reviews__item">
                                    <div className="driver-reviews__user">
                                        <p className="text-content text-primary text-left">{review.pseudo}</p>
                                        <p className="text-small text-silent text-left">{review.comment}</p>
                                    </div>
                                    <div className="driver-reviews__rating">
                                        {Array.from({ length: 5}).map((_, i) => (
                                            <Star
                                                key={i}
                                                className={i < review.rating ? "star--filled" : "star--empty"}
                                                fill="currentColor"
                                            />
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </Section>
        </>
    )
}