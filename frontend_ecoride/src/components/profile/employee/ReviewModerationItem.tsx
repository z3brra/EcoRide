import type { JSX } from "react"

import {
    Calendar,
    Mail,
    User,
    Star,
    CheckCircle2,
    XCircle
} from "lucide-react"

import { Button } from "@components/form/Button"

import type { EmployeeReview } from "@models/review"

import { formatDate } from "@utils/formatters"



export interface ReviewModerationItemProps {
    review: EmployeeReview
    onValidate: (uuid: string) => void
    onRefuse: (uuid: string) => void
}

export function ReviewModerationItem({
    review,
    onValidate,
    onRefuse
}: ReviewModerationItemProps): JSX.Element {
    const formattedDate = formatDate(review.createdAt)

    const safeComment = typeof review.comment === "string" && review.comment.trim().length > 0
        ? review.comment
        : "Aucun commentaire fourni."

    const handleValidate = () => onValidate(review.uuid)
    const handleRefuse = () => onRefuse(review.uuid)

    return (
        <>
            <div className="moderation-review-item">
                <div className="moderation-review-item__top">
                    <span className="moderation-review-item__reference text-small">
                        {review.drive.reference}
                    </span>

                    <div className="moderation-review-item__date">
                        <Calendar size={16} className="icon-primary" />
                        <span className="text-small text-silent">
                            {formattedDate}
                        </span>
                    </div>
                </div>
                <div className="moderation-review-item__users">
                    <div className="moderation-review-item__user">
                        <div className="moderation-review-item__user-header">
                            <div className="moderation-review-item__avatar moderation-review-item__avatar--driver">
                                <User size={18} />
                            </div>
                            <div className="moderation-review-item__user-text">
                                <p className="text-content text-silent text-left">
                                    Chauffeur
                                </p>
                                <div className="moderation-review-item__user-line">
                                    <User size={16} className="icon-primary" />
                                    <span className="text-small text-primary">
                                        {review.driver.pseudo}
                                    </span>
                                </div>
                                <div className="moderation-review-item__user-line">
                                    <Mail size={16} className="icon-primary" />
                                    <span className="text-small text-silent">
                                        {review.driver.email}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="moderation-review-item__user">
                        <div className="moderation-review-item__user-header">
                            <div className="moderation-review-item__avatar moderation-review-item__avatar--author">
                                <User size={18} />
                            </div>
                            <div className="moderation-review-item__user-text">
                                <p className="text-content text-silent text-left">
                                    Auteur
                                </p>
                                <div className="moderation-review-item__user-line">
                                    <User size={16} className="icon-secondary" />
                                    <span className="text-small text-primary">
                                        {review.author.pseudo}
                                    </span>
                                </div>
                                <div className="moderation-review-item__user-line">
                                    <Mail size={16} className="icon-secondary" />
                                    <span className="text-small text-silent">
                                        {review.author.email}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="moderation-review-item__rating">
                    <p className="text-content text-silent text-left">
                        Note
                    </p>
                    <div className="moderation-review-item__stars">
                        {Array.from({ length: 5}).map((_, index) => (
                            <Star
                                key={index}
                                size={18}
                                className={index < review.rate ? "star--filled" : "star--empty"}
                                fill="currentColor"
                            />
                        ))}
                    </div>
                </div>

                <div className="moderation-review-item__comment">
                    <p className="text-content text-silent text-left">
                        Commentaire
                    </p>
                    <div className="moderation-review-item__comment-box">
                        <p className="text-small text-primary text-left">
                            {safeComment}
                        </p>
                    </div>
                </div>

                <div className="moderation-review-item__actions">
                    <Button
                        variant="primary"
                        icon={<CheckCircle2 size={18} />}
                        onClick={handleValidate}
                    >
                        Valider
                    </Button>

                    <Button
                        variant="delete"
                        icon={<XCircle size={18} />}
                        onClick={handleRefuse}
                    >
                        Refuser
                    </Button>
                </div>
            </div>
        </>
    )
}