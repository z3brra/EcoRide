import { Section } from "@components/common/Section/Section"
import { SectionHeader } from "@components/common/Section/SectionHeader"

// import { Card } from "@components/common/Card/Card"
// import { CardContent } from "@components/common/Card/CardContent"
// import { CardIcon } from "@components/common/Card/CardIcon"

import { DriveSearchCard } from "@components/drive/DriveSearchCard"
import { useState } from "react"
import { DriveResultsPlaceholder } from "@components/drive/DriveResultsPlaceholder"

export function Drives () {
    const [hasSearched, setHasSearched] = useState<boolean>(false)
    const [results, setResults] = useState<any[]>([])

    const handleSearch = (data: {
        from: string;
        to: string;
        date: string
    }) => {
        // console.log("Search drives : ", data)
        setHasSearched(true)
        setResults([])
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
                { !hasSearched && (
                    <DriveResultsPlaceholder />
                )}

                { hasSearched && results.length === 0 && (
                    <DriveResultsPlaceholder noResults />
                )}


            </Section>
        </>
    )
}