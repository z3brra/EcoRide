import type { JSX } from "react"
import { User, Star } from "lucide-react"

import { formatDate, getReviewsLabel } from "@utils/formatters"

export type ReviewItemProps = {
    pseudo: string
    date: string
    rate: number
    comment?: string
    status: string
}

export function ReviewItem({
    pseudo,
    date,
    rate,
    comment,
    status,
}: ReviewItemProps): JSX.Element {

    const statusLabel = getReviewsLabel(status)

    return (
        <div className="review-item">
            <div className="review-item__top">
                <div className="review-item__user">
                    <div className="review-item__user-icon">
                        <User size={22} />
                    </div>
                    <div className="review-item__user-info">
                        <span className="text-content text-primary">
                            {pseudo}
                        </span>
                        <span className="text-small text-silent">
                            {formatDate(date)}
                        </span>
                    </div>
                </div>

                <div className="review-item__right">
                    <span className={`review-item__status ${statusLabel.className} text-small`}>
                        {statusLabel.text}
                    </span>

                    <div className="review-item__stars">
                        {Array.from({ length: 5 }).map((_, i) => (
                            <Star
                                key={i}
                                size={18}
                                className={i < rate ? "star--filled" : "star--empty"}
                                fill="currentColor"
                            />
                        ))}
                    </div>
                </div>
            </div>

            { comment && (
                <p className="review-item__comment text-content text-primary text-left">
                    { comment }
                </p>
            )}

        </div>
    )
}