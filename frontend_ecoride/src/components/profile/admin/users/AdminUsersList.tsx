import type { JSX } from "react"

import type { ReadUserResponse } from "@models/user"

import { AdminUserItem } from "./AdminUsersItem"

export type AdminUserListProps = {
    data: ReadUserResponse[]
    loading: boolean
    onBan?: (uuid: string) => void
    onUnban?: (uuid: string) => void
}

export function AdminUserList({
    data,
    loading,
    onBan,
    onUnban
}: AdminUserListProps): JSX.Element {
    if (loading) {
        return (
            <>
                <div className="admin-user-list__loading">
                    <p className="text-content text-silent">
                        Chargement...
                    </p>
                </div>
            </>
        )
    }

    if (!data || data.length === 0) {
        return (
            <>
                <div className="admin-user-list__empty">
                    <p className="text-content text-silent">
                        Aucun utilisateur.
                    </p>
                </div>
            </>
        )
    }

    return (
        <>
            <div className="admin-user-list">
                {data.map((user) => (
                    <AdminUserItem
                        key={user.uuid}
                        user={user}
                        onBan={onBan}
                        onUnban={onUnban}
                        loading={loading}
                    />
                ))}
            </div>
        </>
    )
}
