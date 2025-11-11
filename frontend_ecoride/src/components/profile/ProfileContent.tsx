import type { JSX } from "react"
import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Star } from "lucide-react"

import type { ProfileTab } from "@pages/Profile/Profile"

import { useUpdateProfile } from "@hook/user/useUpdateProfile"
import { useBecomeDriver } from "@hook/user/useBecomeDriver"
import { usePassengerDrives } from "@hook/user/usePassengerDrives"

import { useLeaveDrive } from "@hook/drive/useLeaveDrive"

import { useVehicles } from "@hook/vehicle/useVehicles"

import type { CurrentUserResponse } from "@models/user"

import { PassengerBookingList } from "./bookings/PassengerBookingList"
import { PassengerBookingFilter } from "./bookings/PassengerBookingFilter"

import { VehicleList } from "./vehicles/VehicleList"
import { Pagination } from "@components/common/Pagination/Pagination"

export type ProfileContentProps = {
    user: CurrentUserResponse
    activeTab: ProfileTab
    isDriver: boolean
}

export function ProfileContent({
    user,
    activeTab,
    isDriver
}: ProfileContentProps): JSX.Element {

    const [pseudo, setPseudo] = useState<string>(user.pseudo)
    const [oldPassword, setOldPassword] = useState<string>("")
    const [newPassword, setNewPassword] = useState<string>("")
    const [confirmPassword, setConfirmPassword] = useState<string>("")

    const {
        updateProfile,
        loading,
        error,
        success,
        setError,
        setSuccess
    } = useUpdateProfile()

    const {
        activateDriver,
        loading: driverLoading,
        error: driverError,
        success: driverSuccess,
        setError: setDriverError,
        setSuccess: setDriverSuccess,
    } = useBecomeDriver()

    const {
        cancelBooking,
        loading: leaveLoading,
        error: leaveError,
        success: leaveSuccess,
        setError: setLeaveError,
        setSuccess: setLeaveSuccess,
    } = useLeaveDrive()


    const {
        data: bookings,
        filters,
        totalPages: bookingsTotalPages,
        loading: bookingsLoading,
        error: bookingsError,
        setError: setBookingsError,
        changePage: changeBookingPage,
        updateFilters,
    } = usePassengerDrives()

    const {
        data: vehicle,
        page: vehiclePage,
        totalPages: vehicleTotalPages,
        loading: vehicleLoading,
        error: vehicleError,
        setError: setVehicleError,
        changePage: changeVehiclePage,
    } = useVehicles({ enabled: isDriver })

    const handleSavePseudo = async () => {
        if (pseudo.trim() === "") {
            return setError("Le pseudo ne peut pas être vide.")
        }
        await updateProfile({ pseudo })
    }

    const handleBecomeDriver = async () => {
        await activateDriver()
    }

    const handleSavePassword = async () => {
        if (!oldPassword || !newPassword || !confirmPassword) {
            return setError("Tous les champs sont requis.")
        }
        if (newPassword !== confirmPassword) {
            return setError("Les mots de passe ne correspondent pas.")
        }
        await updateProfile({ oldPassword, newPassword })
        setOldPassword("")
        setNewPassword("")
        setConfirmPassword("")
    }

    const handleCancelBooking = async (uuid: string) => {
        await cancelBooking(uuid)
        if (!leaveError) {
            setTimeout(() => {
                changeBookingPage(filters.page ?? 1)
            }, 500)
        }
    }


    return (
        <div className="profile__content">
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            { success && (
                <MessageBox variant="success" message={success} onClose={() => setSuccess(null)} />
            )}

            { driverError && (
                <MessageBox variant="error" message={driverError} onClose={() => setDriverError(null)} />
            )}

            { driverSuccess && (
                <MessageBox variant="success" message={driverSuccess} onClose={() => setDriverSuccess(null)} />
            )}

            { bookingsError && (
                <MessageBox variant="error" message={bookingsError} onClose={() => setBookingsError(null)} />
            )}

            { leaveError && (
                <MessageBox variant="error" message={leaveError} onClose={() => setLeaveError(null)} />
            )}

            { leaveSuccess && (
                <MessageBox variant="success" message={leaveSuccess} onClose={() => setLeaveSuccess(null)} />
            )}

            { isDriver && vehicleError && (
                <MessageBox variant="error" message={vehicleError} onClose={() => setVehicleError(null)} />
            )}

            { activeTab === "infos" && (
                <Card className="profile__section">
                    <CardContent gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Informations personnelles
                        </h3>
                        <p className="text-small text-silent text-left">
                            Mettez à jour les informations relatives à votre compte.
                        </p>

                        <Input
                            label="Pseudo"
                            value={pseudo}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setPseudo(event.currentTarget.value)}
                        />
                        <div className="profile__actions">
                            { !isDriver && (
                                <Button
                                    variant="secondary"
                                    disabled={driverLoading}
                                    onClick={handleBecomeDriver}
                                >
                                    {driverLoading ? "Activation..." : "Devenir chauffeur"}
                                </Button>
                            )}
                            <Button
                                variant="primary"
                                disabled={loading}
                                onClick={handleSavePseudo}
                            >
                                Sauvegarder
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            )}

            { activeTab === "security" && (
                <Card className="profile__section">
                    <CardContent  gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Modifier le mot de passe
                        </h3>
                        <p className="text-small text-silent text-left">
                            Mettez à jour votre mot de passe pour sécuriser votre compte
                        </p>

                        <Input
                            type="password"
                            label="Mot de passe actuel"
                            value={oldPassword}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setOldPassword(event.currentTarget.value)}
                        />
                        <Input
                            type="password"
                            label="Nouveau mot de passe"
                            value={newPassword}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setNewPassword(event.currentTarget.value)}
                        />
                        <Input
                            type="password"
                            label="Confirmer le mot de passe"
                            value={confirmPassword}
                            onChange={(event: React.ChangeEvent<HTMLInputElement>) => setConfirmPassword(event.currentTarget.value)}
                        />
                        <div className="profile__actions">
                            <Button
                                variant="primary"
                                disabled={loading}
                                onClick={handleSavePassword}
                            >
                                Sauvegarder
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            )}

            { activeTab === "bookings" && (
                <Card className="profile__section">
                    <CardContent  gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Mes réservations
                        </h3>
                        <p className="text-small text-silent text-left">
                            Voyages auxquels vous avez participé en tant que passager.
                        </p>

                        <PassengerBookingFilter
                            filters={filters}
                            onChange={updateFilters}
                        />

                        <PassengerBookingList
                            data={bookings}
                            loading={bookingsLoading || leaveLoading}
                            onCancel={handleCancelBooking}
                        />

                        {!bookingsLoading && bookingsTotalPages > 1 && (
                            <Pagination
                                currentPage={filters.page!}
                                totalPages={bookingsTotalPages}
                                onPageChange={changeBookingPage}
                            />
                        )}

                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "vehicles" && (
                <Card className="profile__section">
                    {/* <CardContent direction="row" justify="between" align="center" gap={1}> */}
                    <CardContent gap={1}>
                        <div className="profile__section-header">
                            <div>
                                <h3 className="text-subtitle text-primary text-left">
                                    Mes véhicules
                                </h3>
                                <p className="text-small text-silent text-left">
                                    Gérez vos véhicules enregistrés.
                                </p>
                            </div>
                            <Button
                                variant="primary"
                                onClick={() => {}}
                            >
                                Ajouter un véhicule
                            </Button>
                        </div>

                        <VehicleList
                            data={vehicle}
                            loading={vehicleLoading}
                            onEdit={(uuid) => console.log("Modifier véhicule :", uuid)}
                            onDelete={(uuid) => console.log("Supprimer véhicule :", uuid)}
                        />

                        {!vehicleLoading && vehicleTotalPages > 1 && (
                            <Pagination
                                currentPage={vehiclePage}
                                totalPages={vehicleTotalPages}
                                onPageChange={changeVehiclePage}
                            />
                        )}
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "drives" && (
                <Card className="profile__section">
                    <CardContent direction="row" justify="between" align="center" gap={1}>
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Mes trajets
                            </h3>
                            <p className="text-small text-silent text-left">
                                Gérez vos trajets en covoiturage.
                            </p>
                        </div>
                        <Button
                            variant="primary"
                            onClick={() => {}}
                        >
                            Créer un trajet
                        </Button>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "reviews" && (
                <Card className="profile__section">
                    <CardContent gap={1}>
                        <div className="profile__reviews-header">
                            <div>
                                <h3 className="text-subtitle text-primary text-left">
                                    Mes avis
                                </h3>
                                <p className="text-small text-silent text-left">
                                    Commentaires de vos passagers.
                                </p>
                            </div>
                            <div className="profile__reviews-score">
                                <Star fill="currentColor" className="text-yellow" />
                                <span className="text-bigcontent text-primary">4.6</span>
                                <p className="text-small text-silent">5 avis</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            )}

            { isDriver && activeTab === "preferences" && (
                <div className="profile__preferences">
                    <Card>
                        <CardContent gap={1}>
                            <h3 className="text-subtitle text-primary text-left">
                                Préférences fixes
                            </h3>
                            <p className="text-small text-silent text-left">
                                Définissez vos préférences de conduite standard.
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardContent gap={1}>
                            <h3 className="text-subtitle text-primary text-left">
                                Préférences personalisées
                            </h3>
                            <p className="text-small text-silent text-left">
                                Ajoutez vos préférences de trajet.
                            </p>
                        </CardContent>
                    </Card>
                </div>
            )}
        </div>
    )
}