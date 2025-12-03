import type { JSX } from "react"

import { Select } from "@components/form/Select"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { ArrowUpDown } from "lucide-react"
import type { DriverOwnedFilters } from "@models/drive"

export type DriverDrivesFilterProps = {
    filters: DriverOwnedFilters
    onChange: (filters: DriverOwnedFilters) => void
    onSearch: () => void
}

export function DriverDrivesFilter({
    filters,
    onChange,
    onSearch,
}: DriverDrivesFilterProps): JSX.Element {
    const handleChange = (key:  keyof DriverOwnedFilters, value: any) => {
        onChange({
            ...filters,
            [key]: value,
        })
    }

    return (
        <div className="booking-filters">
            <div className="booking-filters__group">
                <Select
                    label="Statut"
                    value={filters.status ?? "all"}
                    onChange={(val) => handleChange("status", val)}
                    options={[
                        { label: "Tous", value: "all" },
                        { label: "En attente", value: "open" },
                        { label: "En cours", value: "in_progress" },
                        { label: "Terminés", value: "finished" },
                        { label: "Annulés", value: "cancelled" },
                    ]}
                />
            </div>

            <div className="booking-filters__group">
                <Input
                    label="Départ"
                    placeholder="Ex : Paris"
                    value={filters.depart}
                    onChange={(e: React.ChangeEvent<HTMLInputElement>) => handleChange("depart", e.currentTarget.value)}
                />
            </div>

            <div className="booking-filters__group">
                <Input
                    label="Arrivée"
                    placeholder="Ex : Lyon"
                    value={filters.arrived}
                    onChange={(e: React.ChangeEvent<HTMLInputElement>) => handleChange("arrived", e.currentTarget.value)}
                />
            </div>

            <div className="booking-filters__toggle">
                <Button
                    variant={filters.includeCancelled ? "primary" : "white"}
                    onClick={() => handleChange("includeCancelled", !filters.includeCancelled)}
                >
                    Inclure annulés
                </Button>
            </div>

            <button
                className="booking-filters__sort"
                onClick={() => handleChange("sortDir", filters.sortDir === "desc" ? "asc" : "desc")}
            >
                <ArrowUpDown size={16} />
                <span className="text-small">
                    {filters.sortDir === "desc" ? "Anciens" : "Récents"}
                </span>
            </button>

            <div className="booking-filters__search">
                <Button
                    variant="primary"
                    onClick={() => onSearch()}
                >
                    Rechercher
                </Button>
            </div>
        </div>
    )
}