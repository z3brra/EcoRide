import type { JSX } from "react"
import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Pagination } from "@components/common/Pagination/Pagination"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Modal } from "@components/common/Modal/Modal"
import { DeleteModal } from "@components/common/Modal/DeleteModal"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { VehicleList } from "../vehicles/VehicleList"

import { useVehicles } from "@hook/vehicle/useVehicles"
import { useDeleteVehicle } from "@hook/vehicle/useDeleteVehicle"


type Props = {
    isDriver: boolean
}

export function ProfileVehiclesSection({
    isDriver
}: Props): JSX.Element {
    const [isModalOpen, setIsModalOpen] = useState<boolean>(false)

    const [selectedUuid, setSelectedUuid] = useState<string | null>(null)
    const [isDeleteOpen, setIsDeleteOpen] = useState<boolean>(false)

    const {
        data: vehicle,
        page,
        totalPages,
        loading,
        error,
        setError,
        changePage,
        refresh
    } = useVehicles({ enabled: isDriver })

    const {
        remove,
        loading: deleteLoading,
        error: deleteError,
        success: deleteSuccess,
        setError: setDeleteError,
        setSuccess: setDeleteSuccess,
    } = useDeleteVehicle()

    const handleDeleteConfirm = async () => {
        if (!selectedUuid) {
            return
        }
        await remove(selectedUuid)
        if (!deleteError) {
            setTimeout(() => refresh(), 500)
        }
        setIsDeleteOpen(false)
    }

    return (
        <>
        { isDriver && error && (
            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
        )}

        { isDriver && deleteError && (
            <MessageBox variant="error" message={deleteError} onClose={() => setDeleteError(null)} />
        )}

        { isDriver && deleteSuccess && (
            <MessageBox variant="success" message={deleteSuccess} onClose={() => setDeleteSuccess(null)} />
        )}

        <Card className="profile__section">
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
                        onClick={() => setIsModalOpen(true)}
                    >
                        Ajouter un véhicule
                    </Button>
                </div>

                <VehicleList
                    data={vehicle}
                    loading={loading}
                    onEdit={(uuid) => console.log("Modifier véhicule :", uuid)}
                    onDelete={(uuid) => {
                        setSelectedUuid(uuid)
                        setIsDeleteOpen(true)
                    }}
                />

                {!loading && totalPages > 1 && (
                    <Pagination
                        currentPage={page}
                        totalPages={totalPages}
                        onPageChange={changePage}
                    />
                )}
            </CardContent>
        </Card>

        <Modal
            isOpen={isModalOpen}
            onClose={() => setIsModalOpen(false)}
            title="Ajouter un véhicule"
            width="500px"
        >
            <Input label="Plaque d'immatriculation" placeholder="AB-123-CD" />
            <Input label="Couleur" placeholder="Bleu" />
            <Input label="Nombre de places" type="number" />
            <div className="profile__actions">
                <Button variant="white" onClick={() => setIsModalOpen(false)}>
                    Annuler
                </Button>
                <Button variant="primary" onClick={() => setIsModalOpen(false)}>
                    Enregistrer
                </Button>
            </div>
        </Modal>

        <DeleteModal
            isOpen={isDeleteOpen}
            onClose={() => setIsDeleteOpen(false)}
            onConfirm={handleDeleteConfirm}
            title="Êtes-vous sûr de vouloir supprimer le véhicule"
            description="Cette action est irréversible. Cela supprimera le véhicule et toutes les données associées."
            loading={deleteLoading}
        />
        </>
    )
}