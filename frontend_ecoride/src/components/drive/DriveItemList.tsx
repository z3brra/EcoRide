import type { JSX } from "react"

import { DriveItem } from "./DriveItem"

import { Section } from "@components/common/Section/Section"
import type { Drive } from "@models/drive"

type DriveListProps = {
    items: Drive[]
}

export function DriveItemList({
    items
}: DriveListProps): JSX.Element {

    return (
        <Section id="drive-list">
            <div className="drive-item-list">
                { items.map((items) => (
                    <DriveItem key={items.uuid} item={items} />
                ))}
            </div>
        </Section>
    )
}