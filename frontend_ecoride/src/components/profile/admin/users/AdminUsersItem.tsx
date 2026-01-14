import type { JSX } from "react"

import {
    Calendar,
    Mail,
    User,
    Ban,
    CheckCircle2,
    Coins,
    Car,
    Shield
} from "lucide-react"

import { Button } from "@components/form/Button"

import type { ReadUserResponse } from "@models/user"

import { formatDate } from "@utils/formatters"
import { getRoleTag } from "@utils/roles"

export type AdminUserItemProps = {
    user: ReadUserResponse
    onBan?: (uuid: string) => void
    onUnban?: (uuid: string) => void
    loading?: boolean
}

export function AdminUserItem({
    user,
    onBan,
    onUnban,
    loading = false
}: AdminUserItemProps): JSX.Element {
    const memberSince = formatDate(user.createdAt)

    return (
        <>
            <div className="admin-user-item">
                <div className="admin-user-item__top">
                    <div className="admin-user-item__identity">
                        <div className="admin-user-item__avatar">
                            <User size={18} />
                        </div>

                        <div className="admin-user-item__main">
                            <p className="text-bigcontent text-primary text-left">
                                {user.pseudo}
                            </p>

                            <div className="admin-user-item__line">
                                <Mail size={16} className="icon-primary"/>
                                <span className="text-small text-silent">
                                    {user.email}
                                </span>
                            </div>

                            <div className="admin-user-item__line">
                                <Calendar size={16} className="icon-primary" />
                                <span className="text-small text-silent">
                                    Membre depuis {memberSince}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="admin-user-item__meta">
                        { user.isBanned && (
                            <span className="admin-user-item__status admin-user-item__status--banned text-small">
                                Banni
                            </span>
                        )}

                        { !!user.credits && user.credits > 0 && (
                            <span className="admin-user-item__credits text-small">
                                <Coins size={16} />
                                {user.credits} crédit{user.credits > 1 ? "s" : ""}
                            </span>
                        )}
                    </div>
                </div>

                <div className="admin-user-item__roles">
                    {user.roles?.length > 0 ? (
                        user.roles.map((role) => {
                            const tag = getRoleTag(role)
                            if (!tag) {
                                return null
                            }

                            const Icon =
                                role === "ROLE_DRIVER" ? Car :
                                role === "ROLE_EMPLOYEE" ? User :
                                Shield
                            
                            return (
                                <div
                                    key={role}
                                    className={`admin-user-item__role-tag ${tag.className} text-small`}
                                >
                                    <Icon size={14} />
                                    <span>{tag.label}</span>
                                </div>
                            )
                        })
                    ): (
                        <span className="text-small text-silent">
                            Aucun rôle
                        </span>
                    )}
                </div>

                {(onBan || onUnban) && (
                    <div className="admin-user-item__actions">
                        {user.isBanned ? (
                            <Button
                                variant="primary"
                                icon={<CheckCircle2 size={18} />}
                                onClick={() => onUnban?.(user.uuid)}
                                disabled={loading || !onUnban}
                            >
                                Débannir
                            </Button>
                        ): (
                            <Button
                                variant="delete"
                                icon={<Ban size={18} />}
                                onClick={() => onBan?.(user.uuid)}
                                disabled={loading || !onBan}
                            >
                                Bannir
                            </Button>
                        )}
                    </div>
                )}
            </div>
        </>
    )
}