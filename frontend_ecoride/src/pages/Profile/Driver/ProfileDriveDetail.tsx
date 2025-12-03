import type { JSX } from "react"
import { useState } from "react"

import { Section } from "@components/common/Section/Section"

import { ReturnLink } from "@components/common/ReturnLink"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { EditDriveModal } from "@components/profile/drives/EditDriveModal"
import { StartDriveModal } from "@components/profile/drives/StartDriveModal"
import { CancelDriveModal } from "@components/profile/drives/CancelDriveModal"
import { FinishDriveModal } from "@components/profile/drives/FinishDriveModal"

import {
    MapPin,
    Calendar,
    Coins,
    Car,
    Hash,
    Users,
    PlayCircle,
    Ban,
    PenSquare,
    BookmarkCheck
} from "lucide-react"

import { formatDate, formatTime, formatColor, getStatusLabel } from "@utils/formatters"
import { PROFILE_ROUTES } from "@routes/paths"

import { useDriveDetail } from "@hook/drive/useDriveDetail"
import { useUpdateDrive } from "@hook/drive/useUpdateDrive"
import { useStartDrive } from "@hook/drive/useStartDrive"
import { useCancelDrive } from "@hook/drive/useCancelDrive"
import { useFinishDrive } from "@hook/drive/useFinishDrive"

export function ProfileDriveDetail(): JSX.Element {
    const { drive, loading, error, refresh } = useDriveDetail()

    const [updateOpen, setUpdateOpen] = useState<boolean>(false)
    const [startOpen, setStartOpen] = useState<boolean>(false)
    const [cancelOpen, setCancelOpen] = useState<boolean>(false)
    const [finishOpen, setFinishOpen] = useState<boolean>(false)

    const {
        submit: updateDrive,
        loading: updateLoading,
        error: updateError,
        success: updateSuccess,
        setError: setUpdateError,
        setSuccess: setUpdateSuccess
    } = useUpdateDrive()

    const {
        submit: startDrive,
        loading: startLoading,
        error: startError,
        success: startSuccess,
        setError: setStartError,
        setSuccess: setStartSuccess
    } = useStartDrive()

    const {
        submit: cancelDrive,
        loading: cancelLoading,
        error: cancelError,
        success: cancelSuccess,
        setError: setCancelError,
        setSuccess: setCancelSuccess
    } = useCancelDrive()

    const {
        submit: finishDrive,
        loading: finishLoading,
        error: finishError,
        success: finishSuccess,
        setError: setFinishError,
        setSuccess: setFinishSuccess
    } = useFinishDrive()

    const handleUpdateDrive = async (payload: { departAt: string; arrivedAt: string }) => {
        if (!drive) {
            return
        }

        await updateDrive(drive.uuid, payload)

        if (!updateError) {
            setUpdateOpen(false)
            refresh()
        }
    }

    const handleStart = async () => {
        if (!drive) {
            return
        }

        await startDrive(drive.uuid)

        if (!startError) {
            setStartOpen(false)
            refresh()
        }
    }

    const handleCancel = async () => {
        if (!drive) {
            return
        }

        await cancelDrive(drive.uuid)

        if (!cancelError) {
            setCancelOpen(false)
            refresh()
        }
    }

    const handleFinish = async () => {
        if (!drive) {
            return
        }
        
        await finishDrive(drive.uuid)

        if (!finishError) {
            setFinishOpen(false)
            refresh()
        }
    }

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
    const canFinish = drive.status === "in_progress"

    return (
        <>
            { updateError && (
                <MessageBox variant="error" message={updateError} onClose={()  => setUpdateError(null)} />
            )}

            { updateSuccess && (
                <MessageBox variant="success" message={updateSuccess} onClose={()  => setUpdateSuccess(null)} />
            )}

            { startError && (
                <MessageBox variant="error" message={startError} onClose={() => setStartError(null)} />
            )}

            { startSuccess && (
                <MessageBox variant="success" message={startSuccess} onClose={() => setStartSuccess(null)} />
            )}

            { cancelError && (
                <MessageBox variant="error" message={cancelError} onClose={() => setCancelError(null)} />
            )}

            { cancelSuccess && (
                <MessageBox variant="success" message={cancelSuccess} onClose={() => setCancelSuccess(null)} />
            )}

            { finishError && (
                <MessageBox variant="error" message={finishError} onClose={() => setFinishError(null)} />
            )}

            { finishSuccess && (
                <MessageBox variant="success" message={finishSuccess} onClose={() => setFinishSuccess(null)} />
            )}


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
                                    onClick={() => setUpdateOpen(true)}
                                >
                                    Modifier le trajet
                                </Button>
                            )}
                            
                            { canStart && (
                                <Button
                                    variant="primary"
                                    icon={<PlayCircle size={18}/>}
                                    onClick={() => setStartOpen(true)}
                                >
                                    Démarrer le trajet
                                </Button>
                            )}

                            { canCancel && (
                                <Button
                                    variant="delete"
                                    icon={<Ban size={18}/>}
                                    onClick={() => setCancelOpen(true)}
                                >
                                    Annuler le trajet
                                </Button>
                            )}

                            { canFinish && (
                                <Button
                                    variant="secondary"
                                    icon={<BookmarkCheck size={18}/>}
                                    onClick={() => setFinishOpen(true)}
                                >
                                    Terminer le trajet
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

            <EditDriveModal
                drive={drive}
                isOpen={updateOpen}
                onClose={() => setUpdateOpen(false)}
                onSubmit={handleUpdateDrive}
                loading={updateLoading}
            />

            <StartDriveModal
                isOpen={startOpen}
                onClose={() => setStartOpen(false)}
                onConfirm={handleStart}
                loading={startLoading}
            />

            <CancelDriveModal
                isOpen={cancelOpen}
                onClose={() => setCancelOpen(false)}
                onConfirm={handleCancel}
                loading={cancelLoading}
            />

            <FinishDriveModal
                isOpen={finishOpen}
                onClose={() => setFinishOpen(false)}
                onConfirm={handleFinish}
                loading={finishLoading}
            />
        </>
    )
}