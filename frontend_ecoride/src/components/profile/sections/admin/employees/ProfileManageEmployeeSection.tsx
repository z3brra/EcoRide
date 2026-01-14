import type { JSX } from "react"
import { useCallback, useState } from "react"

import { PlusCircle } from "lucide-react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Button } from "@components/form/Button"

import { MessageBox } from "@components/common/MessageBox/MessageBox"

import { Pagination } from "@components/common/Pagination/Pagination"

import { AdminUserList } from "@components/profile/admin/users/AdminUsersList"

import { CreateEmployeeModal } from "@components/profile/admin/users/CreateEmployeeModal"
import { ConfirmUserBanModal } from "@components/profile/admin/users/ConfirmUserBanModal"
import { ConfirmUserUnbanModal } from "@components/profile/admin/users/ConfirmUserUnbanModal"

import { useEmployee } from "@hook/admin/users/useEmployee"
import { useCreateEmployee } from "@hook/admin/users/useCreateEmployee"
import { useBanUser } from "@hook/admin/users/useBanUser"
import { useUnbanUser } from "@hook/admin/users/useUnbanUser"


export function ProfileManageEmployeeSection(): JSX.Element {

    const {
        employees,
        page,
        totalPages,
        loading,
        error,
        refresh,
        changePage,
        setError
    } = useEmployee()

    const {
        create,
        loading: createLoading,
        error: createError,
        success: createSucces,
        setError: setCreateError,
        setSuccess: setCreateSuccess
    } = useCreateEmployee()

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


    const [isCreateOpen, setIsCreateOpen] = useState<boolean>(false)
    const [isBanOpen, setIsBanOpen] = useState<boolean>(false)
    const [isUnbanOpen, setIsUnbanOpen] = useState<boolean>(false)

    const [createdCredentials, setCreatedCredentials] = useState<null | { email: string, plainPassword: string }>(null)

    const [selectedUserUuid, setSelectedUserUuid] = useState<string | null>(null)

    const closeAll = () => {
        setIsCreateOpen(false)
        setIsBanOpen(false)
        setIsUnbanOpen(false)
        setCreatedCredentials(null)
        setSelectedUserUuid(null)
    }

    const openCreate = () => {
        setCreateError(null)
        setCreateSuccess(null)
        setIsCreateOpen(true)
    }

    const handleCreateEmployee = useCallback(async (pseudo: string) => {
        const trimmed = pseudo.trim()
        if (!trimmed) {
            setCreateError("Le pseudo est requis.")
            return
        }

        const created = await create({ pseudo: trimmed })
        if (created) {
            // closeAll()
            setCreatedCredentials({
                email: created.email,
                plainPassword: created.plainPassword
            })
            refresh()
        }
    }, [create, refresh, closeAll, setCreateError])

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
            refresh()
        }
    }, [selectedUserUuid, banUser, closeAll, refresh])

    const handleUnban = useCallback(async () => {
        if (!selectedUserUuid) {
            return
        }
        const unbanned = await unbanUser(selectedUserUuid)
        if (unbanned) {
            closeAll()
            refresh()
        }
    }, [selectedUserUuid, unbanUser, closeAll, refresh])

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            { createError && (
                <MessageBox variant="error" message={createError} onClose={() => setCreateError(null)} />
            )}

            { createSucces && (
                <MessageBox variant="success" message={createSucces} onClose={() => setCreateSuccess(null)} />
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
                                Gestion des employés
                            </h3>
                            <p className="text-small text-silent text-left">
                                Gérez vos employés.
                            </p>
                        </div>
                        <Button
                            variant="primary"
                            icon={<PlusCircle size={18}/>}
                            onClick={openCreate}
                        >
                            Créer un employé
                        </Button>
                    </div>

                    <AdminUserList
                        data={employees}
                        loading={loading}
                        onBan={handleOpenBan}
                        onUnban={handleOpenUnban}
                    />

                    { !loading && totalPages > 1 && (
                        <Pagination
                            currentPage={page}
                            totalPages={totalPages}
                            onPageChange={changePage}
                        />
                    )}
                </CardContent>
            </Card>

            <CreateEmployeeModal
                isOpen={isCreateOpen}
                onClose={closeAll}
                onSubmit={handleCreateEmployee}
                loading={createLoading}
                credentials={createdCredentials}
            />

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