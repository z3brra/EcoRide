import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Star } from "lucide-react"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Pagination } from "@components/common/Pagination/Pagination"

import { ReviewList } from "../reviews/ReviewItemList"
import { useDriverReviews } from "@hook/review/useDriverReviews"


export function ProfileReviewSection(): JSX.Element {
    const {
        reviews,
        totalReviews,
        averageRate,
        loading,
        error,
        page,
        totalPages,
        changePage,
        setError,
    } = useDriverReviews()


    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => {setError(null)}} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <div className="profile__reviews-header">
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Mes avis
                            </h3>
                            <p className="text-small text-silent text-left">
                                Commentaires de vos passagers.
                            </p>
                        </div>
                        <div className="profile__reviews-score">
                            <Star fill="currentColor" className="text-yellow" />
                            <span className="text-bigcontent text-primary">
                                {averageRate.toFixed(1)}
                            </span>
                            <p className="text-small text-silent">
                                {totalReviews} avis
                            </p>
                        </div>
                    </div>

                    <ReviewList data={reviews} loading={loading} />

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