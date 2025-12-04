import type { JSX } from "react"
import { ReviewModerationItem } from "./ReviewModerationItem"

import type { EmployeeReview } from "@models/review"

export interface ReviewModerationListProps {
    reviews: EmployeeReview[]
    loading: boolean
    onValidate: (uuid: string) => void
    onRefuse: (uuid: string) => void
}

export function ReviewModerationList({
    reviews,
    loading,
    onValidate,
    onRefuse
}: ReviewModerationListProps): JSX.Element {
    if (loading) {
        return (
            <>
                <div className="moderation-review-list__loading">
                    <p className="text-content text-silent">
                    Chargement des avis...
                </p>
                </div>
            </>
        )
    }

    if (!reviews || reviews.length === 0) {
        return (
            <>
                <div className="moderation-review-list__empty">
                    <p className="text-content text-silent">
                        Aucun avis pour le moment.
                    </p>
                </div>
            </>
        )
    }

    return (
        <>
            <div className="moderation-review-list">
                { reviews.map((review) => (
                    <ReviewModerationItem
                        key={review.uuid}
                        review={review}
                        onRefuse={onRefuse}
                        onValidate={onValidate}
                    />
                ))}
            </div>
        </>
    )
}