import type { JSX } from "react"
import { useCallback, useState } from "react"

import { RotateCcw, Search } from "lucide-react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { AdminUserList } from "@components/profile/admin/users/AdminUsersList"

import { ConfirmUserBanModal } from "@components/profile/admin/users/ConfirmUserBanModal"
import { ConfirmUserUnbanModal } from "@components/profile/admin/users/ConfirmUserUnbanModal"

import { useSearchUser } from "@hook/admin/users/useSearchUser"
import { useBanUser } from "@hook/admin/users/useBanUser"
import { useUnbanUser } from "@hook/admin/users/useUnbanUser"

export function ProfileManageUsersSection(): JSX.Element {
    const [email, setEmail] = useState<string>("")
    const [isBanOpen, setIsBanOpen] = useState<boolean>(false)
    const [isUnbanOpen, setIsUnbanOpen] = useState<boolean>(false)
    const [selectedUserUuid, setSelectedUserUuid] = useState<string | null>(null)
    

    const {
        user,
        hasSearched,
        search,
        reset,
        loading,
        error,
        setError
    } = useSearchUser()

    const {
        submit: banUser,
        loading: banLoading,
        error: banError,
        success: banSuccess,
        setError: setBanError,
        setSuccess: setBanSuccess
    } = useBanUser()

    const {
        submit: unbanUser,
        loading: unbanLoading,
        error: unbanError,
        success: unbanSuccess,
        setError: setUnbanError,
        setSuccess: setUnbanSuccess
    } = useUnbanUser()

    const handleSearch = useCallback(async () => {
        const trimmed = email.trim()
        if (!trimmed) {
            return
        }
        await search(trimmed)
    }, [email, search, setError])

    const handleKeyDown = (event: React.KeyboardEvent<HTMLInputElement>) => {
        if (event.key === "Enter" && email.trim() && !loading) {
            handleSearch()
        }
    }

    const resetSearch = useCallback(() => {
        setEmail("")
        reset()
    }, [reset])

    const closeAll = () => {
        setIsBanOpen(false)
        setIsUnbanOpen(false)
        setSelectedUserUuid(null)
    }

    const handleOpenBan = (uuid: string) => {
        setSelectedUserUuid(uuid)
        setIsBanOpen(true)
        setIsUnbanOpen(false)
    }

    const handleOpenUnban = (uuid: string) => {
        setSelectedUserUuid(uuid)
        setIsUnbanOpen(true)
        setIsBanOpen(false)
    }

    const handleBan = useCallback(async () => {
        console.log(selectedUserUuid)
        if (!selectedUserUuid) {
            return
        }
        const banned = await banUser(selectedUserUuid)
        if (banned) {
            closeAll()
            handleSearch()
        }
    }, [selectedUserUuid, banUser, closeAll, handleSearch])

    const handleUnban = useCallback(async () => {
        if (!selectedUserUuid) {
            return
        }
        const unbanned = await unbanUser(selectedUserUuid)
        if (unbanned) {
            closeAll()
            handleSearch()
        }
    }, [selectedUserUuid, unbanUser, closeAll, handleSearch])

    const data = user ? [user] : []



    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            { banError && (
                <MessageBox variant="error" message={banError} onClose={() => setBanError(null)} />
            )}

            { banSuccess && (
                <MessageBox variant="success" message={banSuccess} onClose={() => setBanSuccess(null)} />
            )}

            { unbanError && (
                <MessageBox variant="error" message={unbanError} onClose={() => setUnbanError(null)} />
            )}

            { unbanSuccess && (
                <MessageBox variant="success" message={unbanSuccess} onClose={() => setUnbanSuccess(null)} />
            )}

            <Card className="profile__section">
                <CardContent gap={1}>
                    <div className="profile__section-header">
                        <div>
                            <h3 className="text-subtitle text-primary text-left">
                                Gestion des utilisateurs
                            </h3>
                            <p className="text-small text-silent text-left">
                                Gérer les utilisateurs de la plateforme.
                            </p>
                        </div>
                    </div>

                    <div className="admin-users-search">
                        <div className="admin-users-search__input">
                            <Input
                                type="email"
                                label="Rechercher un utilisateur"
                                placeholder="email@exemple.com"
                                value={email}
                                onChange={(event: React.ChangeEvent<HTMLInputElement>) => setEmail(event.currentTarget.value)}
                                onKeyDown={handleKeyDown}
                            />
                        </div>

                        <div className="admin-users-search__actions">
                            <Button
                                variant="primary"
                                icon={<Search size={18} />}
                                onClick={handleSearch}
                                disabled={loading || !email.trim()}
                            >
                                { loading ? "Recherche..." : "Rechercher"}
                            </Button>

                            { hasSearched && (
                                <Button
                                    variant="secondary"
                                    icon={<RotateCcw size={18} />}
                                    onClick={resetSearch}
                                    disabled={loading}
                                >
                                    Réinitialiser
                                </Button>
                            )}
                        </div>
                    </div>

                    { !hasSearched && (
                        <p className="text-small text-silent text-left">
                            Recherchez un utilisateur par email pour afficher ses informations.
                        </p>
                    )}

                    { hasSearched && !loading && data.length === 0 && (
                        <p className="text-small text-silent text-left">
                            Aucun utilisateur trouvé.
                        </p>
                    )}

                    { hasSearched && (
                        <AdminUserList
                            data={data}
                            loading={loading}
                            onBan={handleOpenBan}
                            onUnban={handleOpenUnban}
                        />
                    )}
                </CardContent>
            </Card>

            <ConfirmUserBanModal
                isOpen={isBanOpen}
                onClose={closeAll}
                onSubmit={handleBan}
                loading={banLoading}
            />

            <ConfirmUserUnbanModal
                isOpen={isUnbanOpen}
                onClose={closeAll}
                onSubmit={handleUnban}
                loading={unbanLoading}
            />
        </>
    )
}