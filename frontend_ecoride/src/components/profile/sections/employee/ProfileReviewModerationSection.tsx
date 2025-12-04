import type { JSX } from "react"
import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { ReviewModerationList } from "@components/profile/employee/ReviewModerationList"
import { useEmployeeReviews } from "@hook/review/employee/useEmployeeReview"
import { Pagination } from "@components/common/Pagination/Pagination"
import { MessageBox } from "@components/common/MessageBox/MessageBox"
import { useModerateEmployeeReview } from "@hook/review/employee/useModerateEmployeeReview"

export function ProfileReviewModerationSection(): JSX.Element {
    const {
        reviews,
        page,
        totalPages,
        loading,
        error,
        refresh,
        changePage,
        setError,
    } = useEmployeeReviews()

    const {
        loading: moderationLoading,
        error: moderationError,
        success: moderationSuccess,
        validateReview,
        refuseReview,
        setError: setModerationError,
        setSuccess: setModerationSuccess
    } = useModerateEmployeeReview()

    const handleValidate = async (uuid: string) => {
        const ok = await validateReview(uuid)
        if (ok) {
            refresh()
        }
    }

    const handleRefuse = async (uuid: string) => {
        const ok = await refuseReview(uuid)
        if (ok) {
            refresh()
        }
    }

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            { moderationError && (
                <MessageBox variant="error" message={moderationError} onClose={() => setModerationError(null)} />
            )}

            { moderationSuccess && (
                <MessageBox variant="success" message={moderationSuccess} onClose={() => setModerationSuccess(null)} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <div>
                        <h3 className="text-subtitle text-primary text-left">
                            Modération des avis
                        </h3>
                        <p className="text-small text-silent text-left">
                            Approuvez ou rejeter les avis en attente soumis par les utilisateurs.
                        </p>
                    </div>

                    <ReviewModerationList
                        reviews={reviews}
                        onRefuse={handleRefuse}
                        onValidate={handleValidate}
                        loading={loading || moderationLoading}
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
        </>
    )
}