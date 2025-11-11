import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Pagination } from "@components/common/Pagination/Pagination"

import { PassengerBookingFilter } from "../bookings/PassengerBookingFilter"
import { PassengerBookingList } from "../bookings/PassengerBookingList"

import { usePassengerDrives } from "@hook/user/usePassengerDrives"
import { useLeaveDrive } from "@hook/drive/useLeaveDrive"

export function ProfileBookingSection(): JSX.Element {
    const {
        data: bookings,
        filters,
        totalPages,
        loading,
        error,
        setError,
        changePage,
        updateFilters,
    } = usePassengerDrives()

    const {
        cancelBooking,
        loading: leaveLoading,
        error: leaveError,
        success: leaveSuccess,
        setError: setLeaveError,
        setSuccess: setLeaveSuccess,
    } = useLeaveDrive()

    const handleCancelBooking = async (uuid: string) => {
        await cancelBooking(uuid)
        if (!leaveError) {
            setTimeout(() => {
                changePage(filters.page ?? 1)
            }, 500)
        }
    }

    return (
        <>
        { error && (
            <MessageBox variant="error" message={error} onClose={() => setError(null)} />
        )}

        { leaveError && (
            <MessageBox variant="error" message={leaveError} onClose={() => setLeaveError(null)} />
        )}

        { leaveSuccess && (
            <MessageBox variant="success" message={leaveSuccess} onClose={() => setLeaveSuccess(null)} />
        )}

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
                    loading={loading || leaveLoading}
                    onCancel={handleCancelBooking}
                />

                {!loading && totalPages > 1 && (
                    <Pagination
                        currentPage={filters.page!}
                        totalPages={totalPages}
                        onPageChange={changePage}
                    />
                )}

            </CardContent>
        </Card>
        </>
    )
}