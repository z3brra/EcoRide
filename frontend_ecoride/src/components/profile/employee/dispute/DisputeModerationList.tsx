import type { JSX } from "react"
import { DisputeModerationItem } from "./DisputeModerationItem"
import type { DriveDispute } from "@models/dispute"

// import type { DriveDispute } from "@models/dispute"

export type DisputeModerationActionTarget = {
    driveUuid: string
    participantUuid: string
}

export interface DisputeModerationListProps {
    disputes: DriveDispute[]
    loading: boolean
    onValidate: (target: DisputeModerationActionTarget) => void
    onRefuse: (target: DisputeModerationActionTarget) => void
}

export function DisputeModerationList({
    disputes,
    loading,
    onValidate,
    onRefuse
}: DisputeModerationListProps): JSX.Element {
    if (loading) {
        return (
            <>
                <div className="moderation-dispute-list__loading">
                    <p className="text-content text-silent">
                    Chargement des litiges...
                </p>
                </div>
            </>
        )
    }

    if (!disputes || disputes.length === 0) {
        return (
            <>
                <div className="moderation-dispute-list__empty">
                    <p className="text-content text-silent">
                        Aucun litige pour le moment.
                    </p>
                </div>
            </>
        )
    }

    return (
        <>
            <div className="moderation-dispute-list">
                { disputes.map((dispute) => (
                    <DisputeModerationItem
                        key={`${dispute.drive.uuid}-${dispute.participant.uuid}-${dispute.occurredAt}`}
                        dispute={dispute}
                        onRefuse={onRefuse}
                        onValidate={onValidate}
                    />
                )) }
            </div>
        </>
    )
}