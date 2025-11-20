import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Pagination } from "@components/common/Pagination/Pagination"

import { Button } from "@components/form/Button"
import { DriverDrivesFilter } from "../drives/DriverDrivesFilter"
import { DriverDriveList } from "../drives/DriverDriveList"
import { useOwnedDrives } from "@hook/user/useOwnedDrives"
import { MessageBox } from "@components/common/MessageBox/MessageBox"

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

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => {setError(null)}} />
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
                        onClick={() => {}}
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
        </>
        
    )
}