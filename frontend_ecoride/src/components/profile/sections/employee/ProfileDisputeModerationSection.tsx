import type { JSX } from "react"

import { useState } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { MessageBox } from "@components/common/MessageBox/MessageBox"
import { Pagination } from "@components/common/Pagination/Pagination"

import { DisputeModerationList } from "@components/profile/employee/dispute/DisputeModerationList"
import type { DisputeModerationActionTarget } from "@components/profile/employee/dispute/DisputeModerationList"

import { RefuseDisputeModal } from "@components/profile/employee/dispute/RefuseDisputeModal"
import { RefundDisputeModal } from "@components/profile/employee/dispute/RefundDisputeModal"

import { useEmployeeDispute } from "@hook/dispute/employee/useEmployeeDispute"
import { useModerateDispute } from "@hook/dispute/employee/useModerationDispute"


export function ProfileDisputeModerationSection(): JSX.Element {
    const {
        disputes,
        page,
        totalPages,
        loading,
        error,
        refresh,
        changePage,
        setError
    } = useEmployeeDispute()

    const {
        loading: moderateLoading,
        error: moderateError,
        success: moderateSuccess,
        setError: setModerateError,
        setSuccess: setModerateSuccess,
        confirmDispute,
        refundDispute
    } = useModerateDispute()

    const [selected, setSelected] = useState<DisputeModerationActionTarget | null>(null)
    const [isRefundOpen, setIsRefundOpen] = useState<boolean>(false)
    const [isRefuseOpen, setIsRefuseOpen] = useState<boolean>(false)

    const closeAll = () => {
        setIsRefundOpen(false)
        setIsRefuseOpen(false)
        setSelected(null)
    }

    const handleOpenRefund = (target: DisputeModerationActionTarget) => {
        setSelected(target)
        setIsRefundOpen(true)
    }

    const handleOpenRefuse = (target: DisputeModerationActionTarget) => {
        setSelected(target)
        setIsRefuseOpen(true)
    }

    const handleSubmitRefund = async (comment: string) => {
        if (!selected) {
            return
        }
        await refundDispute(selected.driveUuid, selected.participantUuid, comment)
        closeAll()
        refresh()
    }

    const handleSubmitRefuse = async () => {
        if (!selected) {
            return
        }
        await confirmDispute(selected.driveUuid, selected.participantUuid)
        closeAll()
        refresh()
    }

    // const openRefundModal = (target: DisputeModerationActionTarget) => {
    //     setSelected(target)
    //     setIsRefundOpen(true)
    // }

    // const closeRefundModal = () => {
    //     setSelected(null)
    //     setIsRefundOpen(false)
    // }

    // const openRefuseModal = (target: DisputeModerationActionTarget) => {
    //     setSelected(target)
    //     setIsRefuseOpen(true)
    // }

    // const closeRefuseModal = () => {
    //     setSelected(null)
    //     setIsRefuseOpen(false)
    // }

    // const submitRefund = (data: { comment: string }) => {
    //     console.log("Refund submit", { ...selected, ...data })
    //     closeRefundModal()
    // }

    // const submitRefuse = () => {
    //     console.log("Refuse submit", { ...selected })
    //     closeRefuseModal()
    // }


    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)}/>
            )}

            { moderateError && (
                <MessageBox variant="error" message={moderateError} onClose={() => setModerateError(null)} />
            )}

            { moderateSuccess && (
                <MessageBox variant="success" message={moderateSuccess} onClose={() => setModerateSuccess(null)} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <div>
                        <h3 className="text-subtitle text-primary text-left">
                            Modération des litiges
                        </h3>
                        <p className="text-small text-silent text-left">
                            Approuvez ou rejeter les litiges en attente soumis par les utilisateurs.
                        </p>
                    </div>

                    <DisputeModerationList
                        disputes={disputes}
                        onRefuse={handleOpenRefuse}
                        onValidate={handleOpenRefund}
                        loading={loading}
                    />

                    { !loading && totalPages > 1 && (
                        <Pagination
                            currentPage={page}
                            totalPages={totalPages}
                            onPageChange={changePage}
                        />
                    )}
                </CardContent>
            </Card>

            { isRefundOpen && selected && (
                <RefundDisputeModal
                    isOpen={isRefundOpen}
                    onClose={closeAll}
                    onSubmit={handleSubmitRefund}
                    loading={moderateLoading}
                />
            )}

            { isRefuseOpen && selected && (
                <RefuseDisputeModal
                    isOpen={isRefuseOpen}
                    onClose={closeAll}
                    onSubmit={handleSubmitRefuse}
                    loading={moderateLoading}
                />
            )}
        </>
    )
}