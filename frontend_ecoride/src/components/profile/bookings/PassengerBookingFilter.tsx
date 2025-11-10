import type { JSX } from "react"
import { ArrowUpDown } from "lucide-react"
import { Select } from "@components/form/Select"
import { Button } from "@components/form/Button"

import type { DriveJoinedFilters } from "@models/drive"

export type PassengerBookingFilterProps = {
    filters: DriveJoinedFilters
    onChange: (filters: DriveJoinedFilters) => void
}

export function PassengerBookingFilter({
    filters,
    onChange
}: PassengerBookingFilterProps): JSX.Element {
    const handleChange = (
        key: keyof DriveJoinedFilters,
        value: string | boolean
    ) => {
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
                        { label: "Terminé", value: "finished" },
                        { label: "Annulé", value: "cancelled" },
                    ]}
                />
            </div>

            <div className="booking-filters__group">
                <Select
                    label="Période"
                    value={filters.when ?? "all"}
                    onChange={(val) => handleChange("when", val)}
                    options={[
                        { label: "Tous", value: "all" },
                        { label: "A venir", value: "upcoming" },
                        { label: "Passés", value: "past" },
                    ]}
                />
            </div>

            {/* <div className="booking-filters__checkbox">
                <label className="text-small text-silent">
                    <input
                        type="checkbox"
                        checked={filters.includeCancelled}
                        onChange={(event: React.ChangeEvent<HTMLInputElement>) =>
                            handleChange("includeCancelled", event.currentTarget.value)
                        }
                    />
                    Inclure annulés
                </label>
            </div> */}
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
                onClick={() => handleChange("sortDir", filters.sortDir === "asc" ? "desc" : "asc")}
            >
                <ArrowUpDown size={16} />
                <span className="text-small">
                    {filters.sortDir === "asc" ? "Récents" : "Anciens"}
                </span>
            </button>
        </div>
    )
}