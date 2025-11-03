import { useEffect } from "react"
import { useSearchParams } from "react-router-dom"

import { useDriveSearch } from "@hook/drive/useDriveSearch"

import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

import { DriveSearchCard } from "@components/drive/DriveSearchCard"
import { DriveResultsPlaceholder } from "@components/drive/DriveResultsPlaceholder"
import { DriveItemList } from "@components/drive/DriveItemList"

import { Pagination } from "@components/common/Pagination/Pagination"

export function Drives () {
    const {
        data,
        hasSearched,
        page,
        totalPages,
        search,
        changePage,

        loading,
    } = useDriveSearch()

    const [searchParams, setSearchParams] = useSearchParams()

    useEffect(() => {
        const from = searchParams.get("from")
        const to = searchParams.get("to")
        const date = searchParams.get("date")
        const pageParam = searchParams.get("page")

        if (from && to && date) {
            const pageNumber = pageParam ? parseInt(pageParam, 10) : 1
            search({ depart: from, arrived: to, departAt: date }, pageNumber)
        }
    }, [searchParams])

    const handleSearch = (criteria: { from: string; to: string; date: string }) => {
        setSearchParams({
            from: criteria.from,
            to: criteria.to,
            date: criteria.date,
            page: "1"
        })
        
        search({
            depart: criteria.from,
            arrived: criteria.to,
            departAt: criteria.date,
        })
    }

    const handlePageChange = (newPage: number) => {
        const current = Object.fromEntries(searchParams.entries())
        setSearchParams({ ...current, page: String(newPage) })

        changePage(newPage)
    }

    return (
        <>
            <Section id="drive-header">
                <SectionHeader
                    title="Trouver votre covoiturage"
                    titleVariant="headline"
                    animate
                    align="center"
                />
                <DriveSearchCard
                    onSearch={handleSearch}
                    isLoading={loading}
                    defaultFrom={searchParams.get("from") || ""}
                    defaultTo={searchParams.get("to") || ""}
                    defaultDate={searchParams.get("date") || ""}
                />
            </Section>

            <Section id="drive-results">
                { !hasSearched && !loading && (
                    <DriveResultsPlaceholder />
                )}

                { hasSearched && loading && (
                    <DriveResultsPlaceholder isLoading />
                )}

                { hasSearched && !loading && data.length === 0 && (
                    <DriveResultsPlaceholder noResults />
                )}

                {hasSearched && !loading && data.length > 0 && (
                    <DriveItemList items={data}/>
                )}
            </Section>
            { hasSearched && totalPages > 1 && (
                <Pagination
                    currentPage={page}
                    totalPages={totalPages}
                    onPageChange={handlePageChange}
                />
            )}
        </>
    )
}