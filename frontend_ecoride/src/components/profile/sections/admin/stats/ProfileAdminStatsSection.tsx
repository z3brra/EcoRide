import type { JSX } from "react"
import React, { useMemo } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { BarChart3, RefreshCcw } from "lucide-react"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"
import { Select } from "@components/form/Select"

import { PlatformFeeBarChartCard } from "@components/profile/admin/stats/PlatformFeeBarChartCard"

import type { PlatformFeeRange } from "@models/adminStats"

import { usePlatformFeeStats } from "@hook/admin/stats/usePlatformFeeStats"
import { MessageBox } from "@components/common/MessageBox/MessageBox"

const RANGE_OPTIONS: Array<{ value: PlatformFeeRange; label: string }> = [
    { value: "today", label: "Aujourd'hui" },
    { value: "last_7_days", label: "7 derniers jours" },
    { value: "this_month", label: "Ce mois-ci" },
    { value: "last_3_month", label: "3 derniers mois" },
    { value: "this_year", label: "Cette année" },
    { value: "year", label: "Année spécifique" }
]

export function ProfileAdminStatsSection(): JSX.Element {
    const {
        range,
        setRange,
        year,
        setYear,
        data,
        loading,
        error,
        setError,
        refresh
    } = usePlatformFeeStats()

    const isYearRange = range === "year"

    const isYearValid = useMemo(() => {
        if (!isYearRange) {
            return true
        }
        const trimmed = year.trim()
        if (!trimmed) {
            return false
        }
        const n = Number(trimmed)
        return Number.isInteger(n) &&  n >= 2000 && n <= 2100
    }, [isYearRange, year])

    const granularities = useMemo(() => {
        if (!data) {
            return []
        }
        return Array.from(new Set(data.series.map((serie) => serie.granularity)))
    }, [data])

    const total = data?.total ?? 0

    return (
        <div className="admin-stats">
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <div className="admin-stats__header">
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Statistiques
                            </h3>
                            <p className="text-small text-silent text-left">
                                Crédits gagnés selon la période sélectionnée.
                            </p>
                        </div>
                    </div>

                    <div className="admin-stats__controls">
                        <div className="admin-stats__control">
                            <Select
                                label="Période"
                                options={RANGE_OPTIONS}
                                value={range}
                                onChange={(val) => setRange(val as PlatformFeeRange)}
                            />
                        </div>

                        { isYearRange && (
                            <div className="admin-stats__control admin-stats__control--year">
                                <Input
                                    type="number"
                                    label="Année"
                                    placeholder="2026"
                                    value={year}
                                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setYear(event.currentTarget.value)}
                                />
                                { !isYearValid && (
                                    <p className="text-small text-silent text-left">
                                        Veuillez saisir une année valide (ex : 2026)
                                    </p>
                                )}
                            </div>
                        )}

                        <div className="admin-stats__control admin-stats__control--actions">
                            <Button
                                variant="white"
                                icon={<RefreshCcw size={18} />}
                                onClick={refresh}
                                disabled={loading || !isYearValid}
                            >
                                {loading ? "Chargement..." : "Rafraîchir"}
                            </Button>
                        </div>
                    </div>

                    <div className="admin-stats__summary">
                        <div className="admin-stats__summary-item">
                            <p className="text-small text-silent text-left">
                                Granularités de la période
                            </p>

                            <div className="admin-stats__granularities">
                                {granularities.length === 0 && (
                                    <span className="text-small text-silen">
                                        -
                                    </span>
                                )}
                                {granularities.map((granularity) => (
                                    <span key={granularity} className="admin-stats__granularity text-small">
                                        {granularity}
                                    </span>
                                ))}
                            </div>
                        </div>

                        <div className="admin-stats__summary-item">
                            <p className="text-small text-silent text-left">
                                Total sur la période
                            </p>
                            <div className="admin-stats__total">
                                <BarChart3 size={18} className="icon-primary" />
                                <span className="text-bigcontent text-primary">
                                    {total}
                                </span>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <PlatformFeeBarChartCard
                title="Crédits gagnés"
                subtitle="Crédis gagnés par la plateforme"
                timezone={data?.timezone ?? "Europe/Paris"}
                series={data?.series ?? []}
                loading={loading}
            />
        </div>
    )
}