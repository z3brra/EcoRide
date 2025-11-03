import type { JSX } from "react"
import { ChevronLeft, ChevronRight } from "lucide-react"

import { Section } from "@components/common/Section/Section"

import { Button } from "@components/form/Button"

export type PaginationProps = {
    currentPage: number
    totalPages: number
    onPageChange: (page: number) => void
}

export interface PaginatedResponse<T> {
    data: T[]
    total: number
    totalPages: number
    currentPage: number
    perPage: number
    sortBy?: string
    sortDir?: string
}

export function Pagination({
    currentPage,
    totalPages,
    onPageChange
}: PaginationProps): JSX.Element {
    return (
        <Section id="pagination">
            <div className="pagination">
                { currentPage > 1 && (
                    <Button
                        variant="secondary"
                        onClick={() => onPageChange(currentPage - 1)}
                        className="text-content pagination__button"
                        aria-label="Page précédente"
                    >
                        <ChevronLeft size={20} />
                    </Button>
                )}

                <span className="pagination__info text-content text-primary">
                    Page { currentPage } sur { totalPages }
                </span>

                { currentPage < totalPages && (
                    <Button
                        variant="secondary"
                        onClick={() => onPageChange(currentPage + 1)}
                        className="pagination__button text-content"
                    >
                        <ChevronRight size={20} />
                    </Button>
                )}
            </div>
        </Section>
    )
}