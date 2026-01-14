import type { JSX } from "react"

import {
    ResponsiveContainer,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    Tooltip,
    CartesianGrid
} from "recharts"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import type { PlatformFeeSeries } from "@models/adminStats"

type ChartPoint = {
    label: string
    sum: number
}

export type PlatformFeeBarChartCardProps = {
    title: string
    subtitle?: string
    timezone: string
    series: PlatformFeeSeries[]
    loading?: boolean
}

function formatLabel(timestamp: string, granularity: string, timezone: string): string {
    const date = new Date(timestamp)

    switch (granularity) {
        case "month":
            return new Intl.DateTimeFormat("fr-FR", { month: "short", timeZone: timezone }).format(date)
        case "day":
            return new Intl.DateTimeFormat("fr-FR", { day: "2-digit", month: "2-digit", timeZone: timezone }).format(date)
        case "hour":
            return new Intl.DateTimeFormat("fr-FR", { day: "2-digit", month: "2-digit", hour: "2-digit", timeZone: timezone }).format(date)
        case "half_hour":
            return new Intl.DateTimeFormat("fr-FR", { day: "2-digit", month: "2-digit", hour: "2-digit", minute: "2-digit", timeZone: timezone }).format(date)
        case "half_day":
            return new Intl.DateTimeFormat("fr-FR", { day: "2-digit", month: "2-digit", timeZone: timezone }).format(date) + " (½j)"
        default:
            return new Intl.DateTimeFormat("fr-FR", { dateStyle: "short", timeZone: timezone }).format(date)
    }
}

function SeriesChart({
    series,
    timezone
}: { series: PlatformFeeSeries; timezone: string}): JSX.Element {
    const data: ChartPoint[] = series.points.map((point) => ({
        label: formatLabel(point.timestamp, series.granularity, timezone),
        sum: point.sum
    }))

    return (
        <div className="admin-stats__chart-block">
            <div className="admin-stats__chart-block-header">
                <p className="text-content text-primary text-left">
                    Granularité : {series.granularity}
                </p>
                <p className="text-small text-silent text-left">
                    {data.length} point{data.length > 1 ? "s" : ""}
                </p>
            </div>

            <div className="admin-stats__chart-inner">
                <ResponsiveContainer width="100%" height={260}>
                    <BarChart data={data}>
                        <CartesianGrid strokeDasharray="3 3" />
                        <XAxis dataKey="label" />
                        <YAxis />
                        <Tooltip />
                        <Bar dataKey="sum" fill="currentColor" />
                    </BarChart>
                </ResponsiveContainer>
            </div>
        </div>
    )
}

export function PlatformFeeBarChartCard({
    title,
    subtitle,
    timezone,
    series,
    loading = false
}: PlatformFeeBarChartCardProps): JSX.Element {
    const hasData = series?.some((serie) => (serie.points?.length ?? 0) > 0)

    return (
        <Card className="profile__section admin-stats__chart-card">
            <CardContent gap={1}>
                <div className="admin-stats__chart-header">
                    <div>
                        <h3 className="text-subtitle text-primary text-left">
                            {title}
                        </h3>
                        { subtitle && (
                            <p className="text-small text-silent text-left">
                                { subtitle }
                            </p>
                        )}
                    </div>
                </div>

                { loading ? (
                    <p className="text-small text-silent text-left">
                        Chargement du graphique...
                    </p>
                ) : !hasData ? (
                    <p className="text-small text-silent text-left">
                        Aucune donnée à afficher.
                    </p>
                ) : (
                    <div className="admin-stats__chart-stack">
                        { series.map((serie) => (
                            <SeriesChart
                                key={`${serie.key}-${serie.granularity}`}
                                series={serie}
                                timezone={timezone}
                            />
                        ))}
                    </div>
                )}
            </CardContent>
        </Card>
    )
}