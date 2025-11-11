import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Pagination } from "@components/common/Pagination/Pagination"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Button } from "@components/form/Button"

import { VehicleList } from "../vehicles/VehicleList"

import { useVehicles } from "@hook/vehicle/useVehicles"

type Props = {
    isDriver: boolean
}

export function ProfileVehiclesSection({
    isDriver
}: Props): JSX.Element {
    const {
        data: vehicle,
        page,
        totalPages,
        loading,
        error,
        setError,
        changePage,
    } = useVehicles({ enabled: isDriver })

    return (
        <>
        { isDriver && error && (
            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
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
                        onClick={() => {}}
                    >
                        Ajouter un véhicule
                    </Button>
                </div>

                <VehicleList
                    data={vehicle}
                    loading={loading}
                    onEdit={(uuid) => console.log("Modifier véhicule :", uuid)}
                    onDelete={(uuid) => console.log("Supprimer véhicule :", uuid)}
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
        </>
    )
}