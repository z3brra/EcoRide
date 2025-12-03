import type { JSX } from "react"
import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Pagination } from "@components/common/Pagination/Pagination"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Button } from "@components/form/Button"
import { DriverDrivesFilter } from "../drives/DriverDrivesFilter"
import { DriverDriveList } from "../drives/DriverDriveList"

import { useOwnedDrives } from "@hook/user/useOwnedDrives"
import { useAllVehicle } from "@hook/vehicle/useAllVehicle"
import { useCreateDrive } from "@hook/drive/useCreateDrive"

import { CreateDriveModal } from "../drives/CreateDriveModal"


export function ProfileDrivesSection(): JSX.Element {
    const {
        data: drives,
        loading,
        error,
        filters,
        totalPages,
        updateFilters,
        search,
        changePage,
        setError
    } = useOwnedDrives()

    const {
        data: allVehicles,
        error: vehicleError,
        loading: vehicleLoading,
        setError: setVehicleError
    } = useAllVehicle()

    const {
        submit: createDrive,
        loading: createLoading,
        error: createError,
        success: createSuccess,
        setError: setCreateError,
        setSuccess: setCreateSuccess,
    } = useCreateDrive()

    const [isCreateModalOpen, setIsCreateModalOpen] = useState<boolean>(false)

    const handleCreateDrive = async (payload: any) => {
        await createDrive(payload)

        if (!createError) {
            setIsCreateModalOpen(false)
            search()
        }
    }

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => {setError(null)}} />
            )}

            { vehicleError && (
                <MessageBox variant="error" message={vehicleError} onClose={() => {setVehicleError(null)}} />
            )}

            { createError && (
                <MessageBox variant="error" message={createError} onClose={() => {setCreateError(null)}} />
            )}

            { createSuccess && (
                <MessageBox variant="success" message={createSuccess} onClose={() => {setCreateSuccess(null)}} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
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
                        onClick={() => setIsCreateModalOpen(true)}
                    >
                        Créer un trajet
                    </Button>

                    <DriverDrivesFilter
                        filters={filters}
                        onChange={updateFilters}
                        onSearch={search}
                    />

                    <DriverDriveList
                        items={drives}
                        loading={loading}
                    />

                    { !loading && totalPages > 1 && (
                        <Pagination
                            currentPage={filters.page!}
                            totalPages={totalPages}
                            onPageChange={changePage}
                        />
                    ) }
                </CardContent>
            </Card>

            <CreateDriveModal
                isOpen={isCreateModalOpen}
                onClose={() => setIsCreateModalOpen(false)}
                onSubmit={handleCreateDrive}
                vehicles={allVehicles}
                loading={createLoading || vehicleLoading}
            />
        </>
    )
}