import { useDriveSearch } from "@hook/drive/useDriveSearch"

import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

import { DriveSearchCard } from "@components/drive/DriveSearchCard"
import { DriveResultsPlaceholder } from "@components/drive/DriveResultsPlaceholder"
import { DriveItemList } from "@components/drive/DriveItemList"

import { Pagination } from "@components/common/Pagination/Pagination"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

export function Drives () {
    const {
        data,
        hasSearched,
        page,
        totalPages,
        search,
        changePage,

        loading,
        error,
        setError,
    } = useDriveSearch()

    const handleSearch = (criteria: { from: string; to: string; date: string }) => {
        search({
            depart: criteria.from,
            arrived: criteria.to,
            departAt: criteria.date,
        })
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
                <DriveSearchCard onSearch={handleSearch} />
            </Section>

            <Section id="drive-results">
                { !hasSearched && !loading && (
                    <DriveResultsPlaceholder />
                )}

                { hasSearched && !loading && data.length === 0 && (
                    <DriveResultsPlaceholder noResults />
                )}

                { hasSearched && loading && (
                    <DriveResultsPlaceholder isLoading />
                )}

                {hasSearched && !loading && data.length > 0 && (
                    <DriveItemList items={data}/>
                )}
            </Section>
            { hasSearched && totalPages > 1 && (
                <Pagination
                    currentPage={page}
                    totalPages={totalPages}
                    onPageChange={changePage}
                />
            )}
        </>
    )
}