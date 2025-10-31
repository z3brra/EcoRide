import type { JSX } from "react"
import { useState } from "react"
import { MapPin, Calendar, Search } from "lucide-react"
import { Card } from "@components/common/Card/Card"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

export type DriveSearchCardProps = {
    onSearch?: (data: {
        from: string;
        to: string;
        date: string
    }) => void
    isLoading?: boolean
    className?: string
}

export function DriveSearchCard({
    onSearch,
    isLoading,
    className = ""
}: DriveSearchCardProps): JSX.Element {
    const [from, setFrom] = useState<string>("")
    const [to, setTo] = useState<string>("")
    const [date, setDate] = useState<string>("")

    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault()
        if (onSearch) onSearch({ from, to, date })
    }

    return (
        <Card className={`drive-search ${className}`}>
            <form className="drive-search__form" onSubmit={handleSubmit}>
                <Input
                    label="Départ"
                    labelIcon={<MapPin />}
                    placeholder="Ex : Paris"
                    value={from}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setFrom(event.currentTarget.value)}
                    required
                    className="text-content"
                />
                <Input
                    label="Arrivée"
                    labelIcon={<MapPin />}
                    placeholder="Ex : Lyon"
                    value={to}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setTo(event.currentTarget.value)}
                    required
                />
                <Input
                    label="Date de départ"
                    labelIcon={<Calendar />}
                    type="date"
                    value={date}
                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setDate(event.currentTarget.value)}
                    required
                />

                <Button
                    type="submit"
                    variant="primary"
                    icon={<Search />}
                    disabled={isLoading}
                    onClick={() => console.log("search")}
                    className="text-content"
                >
                    { isLoading ? "Recherche..." : "Chercher"}
                </Button>
            </form>
        </Card>
    )
}