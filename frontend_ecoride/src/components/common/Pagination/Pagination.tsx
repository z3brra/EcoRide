import type { JSX } from "react"
import { ChevronLeft, ChevronRight } from "lucide-react"

import { Section } from "@components/common/Section/Section"

import { Button } from "@components/form/Button"

export type PaginationProps = {
    currentPage: number
    totalPages: number
    totalItems?: number
    perPage?: number
    onPageChange: (page: number) => void
}

// export interface PaginatedResponse<T> {
//     data: T[]
//     total: number
//     totalPages: number
//     currentPage: number
//     perPage: number
//     sortBy?: string
//     sortDir?: string
// }

export function Pagination({
    currentPage = 1,
    totalPages = 1,
    totalItems,
    perPage,
    onPageChange
}: PaginationProps): JSX.Element {

    const handlePrevious = () => {
        if (currentPage > 1) {
            onPageChange(currentPage - 1)
        }
    }

    const handleNext = () => {
        if (currentPage < totalPages) {
            onPageChange(currentPage + 1)
        }
    }

    const showTotal = totalItems !== undefined && perPage !== undefined

    return (
        <Section id="pagination">
            <div className="pagination">
                { currentPage > 1 && (
                    <Button
                        variant="secondary"
                        onClick={handlePrevious}
                        className="text-content pagination__button"
                        aria-label="Page précédente"
                    >
                        <ChevronLeft size={20} />
                    </Button>
                )}

                <span className="pagination__info text-content text-primary">
                    Page { currentPage } sur { totalPages }
                    { showTotal && (
                        <span className="text-small text-silent">
                            {" "}
                            ({totalItems} résultats)
                        </span>
                    )}
                </span>

                { currentPage < totalPages && (
                    <Button
                        variant="secondary"
                        onClick={handleNext}
                        className="pagination__button text-content"
                        aria-label="Page suivante"
                    >
                        <ChevronRight size={20} />
                    </Button>
                )}
            </div>
        </Section>
    )
}