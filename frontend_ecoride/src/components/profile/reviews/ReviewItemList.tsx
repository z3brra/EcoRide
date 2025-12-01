import type { JSX } from "react"
import { ReviewItem } from "./ReviewItem"

import type { DriverReview } from "@models/review"

export type ReviewListProps = {
    data: DriverReview[]
    loading?: boolean
}

export function ReviewList({
    data,
    loading = false
}: ReviewListProps): JSX.Element {
    if (loading) {
        return (
            <div className="review-list__loading">
                <p className="text-content text-silent">Chargement des avis...</p>
            </div>
        )
    }

    if (data.length === 0) {
        return (
            <div className="review-list__empty">
                <p className="text-content text-silent">Aucun avis pour le moment.</p>
            </div>
        )
    }

    return (
        <div className="review-list">
            { data.map((review) => (
                <ReviewItem
                    key={review.uuid}
                    pseudo={review.author.pseudo}
                    date={review.createdAt}
                    rate={review.rate}
                    comment={review.comment}
                    status={review.status}
                />
            ))}
        </div>
    )
}