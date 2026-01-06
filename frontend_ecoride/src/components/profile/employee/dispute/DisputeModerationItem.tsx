import type { JSX } from "react"

import { Button } from "@components/form/Button"

import {
    User,
    Calendar,
    Mail,
    CheckCircle2,
    XCircle
} from "lucide-react"

import type { DriveDispute } from "@models/dispute"

import { formatDate } from "@utils/formatters"

import type { DisputeModerationActionTarget } from "./DisputeModerationList"

export interface DisputeModerationItemProps {
    dispute: DriveDispute
    onValidate: (target: DisputeModerationActionTarget) => void
    onRefuse: (target: DisputeModerationActionTarget) => void
}

export function DisputeModerationItem({
    dispute,
    onValidate,
    onRefuse
}: DisputeModerationItemProps): JSX.Element {
    const formattedDate = formatDate(dispute.occurredAt)

    const target: DisputeModerationActionTarget = {
        driveUuid: dispute.drive.uuid,
        participantUuid: dispute.participant.uuid
    }

    return (
        <>
            <div className="moderation-dispute-item">
                <div className="moderation-dispute-item__top">
                    <span className="moderation-dispute-item__reference text-small">
                        {dispute.drive.reference}
                    </span>

                    <div className="moderation-dispute-item__date">
                        <Calendar size={16} className="icon-primary" />
                        <span className="text-small text-silent">
                            {formattedDate}
                        </span>
                    </div>
                </div>
                <div className="moderation-dispute-item__users">
                    <div className="moderation-dispute-item__user">
                        <div className="moderation-dispute-item__user-header">
                            <div className="moderation-dispute-item__avatar moderation-dispute-item__avatar--driver">
                                <User size={18} />
                            </div>
                            <div className="moderation-dispute-item__user-text">
                                <p className="text-content text-silent text-left">
                                    Chauffeur
                                </p>
                                <div className="moderation-dispute-item__user-line">
                                    <User size={16} className="icon-primary" />
                                    <span className="text-small text-primary">
                                        {dispute.drive.owner.pseudo}
                                    </span>
                                </div>
                                <div className="moderation-dispute-item__user-line">
                                    <Mail size={16} className="icon-primary" />
                                    <span className="text-small text-silent">
                                        {dispute.drive.owner.email}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="moderation-dispute-item__user">
                        <div className="moderation-dispute-item__user-header">
                            <div className="moderation-dispute-item__avatar moderation-dispute-item__avatar--participant">
                                <User size={18} />
                            </div>
                            <div className="moderation-dispute-item__user-text">
                                <p className="text-content text-silent text-left">
                                    Participant
                                </p>
                                <div className="moderation-dispute-item__user-line">
                                    <User size={16} className="icon-secondary" />
                                    <span className="text-small text-primary">
                                        {dispute.participant.pseudo}
                                    </span>
                                </div>
                                <div className="moderation-dispute-item__user-line">
                                    <Mail size={16} className="icon-secondary" />
                                    <span className="text-small text-silent">
                                        {dispute.participant.email}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                { dispute.comment && (
                    <div className="moderation-dispute-item__comment">
                        <p className="text-content text-silent text-left">
                            Commentaire
                        </p>
                        <div className="moderation-dispute-item__comment-box">
                            <p className="text-small text-primary text-left">
                                {dispute.comment}
                            </p>
                        </div>
                    </div>
                )}

                <div className="moderation-dispute-item__actions">
                    <Button
                        variant="primary"
                        icon={<CheckCircle2 size={18} />}
                        onClick={() => onValidate(target)}
                    >
                        Rembourser
                    </Button>

                    <Button
                        variant="delete"
                        icon={<XCircle size={18} />}
                        onClick={() => onRefuse(target)}
                    >
                        Refuser
                    </Button>
                </div>
            </div>
        </>
    )
}