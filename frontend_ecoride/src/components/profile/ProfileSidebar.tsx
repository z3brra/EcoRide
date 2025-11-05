import type { JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"
import { User, Car, Wallet, Coins } from "lucide-react"

import type { ProfileTab } from "@pages/Profile/Profile"

import type { CurrentUserResponse } from "@models/user"

export type ProfileSidebarProps = {
    user: CurrentUserResponse
    activeTab: ProfileTab
    setActiveTab: (tab: ProfileTab) => void
    isDriver: boolean
}

export function ProfileSidebar({
    user,
    activeTab,
    setActiveTab,
    isDriver
}: ProfileSidebarProps): JSX.Element {
    return (
        <div className="profile__sidebar">
            <Card className="profile__user-card">
                <CardContent direction="row" justify="start" align="center" gap={1}>
                    <div className="profile__user-avatar">
                        <User size={30} />
                    </div>
                    <div className="profile__user-infos">
                        <h3 className="text-content text-primary">{user.pseudo}</h3>
                        <p className="text-small text-silent">{user.email}</p>
                        { isDriver && (
                            <div className="profile__user-tag text-small">
                                <Car size={14} />
                                <span>Chauffeur</span>
                            </div>
                        )}
                    </div>
                </CardContent>
            </Card>

            <Card className="profile__credit-card">
                <CardContent>
                    <div className="profile__credit-header">
                        <Wallet size={20} className="icon-primary" />
                        <span className="text-bigcontent text-primary text-bold">Crédits</span>
                    </div>
                    <div className="profile__credit-amount">
                        <div className="profile__credit-icon">
                            <Coins size={18} className="icon-primary"/>
                        </div>
                        <div className="profile__credit-text text-left">
                            <span className="text-bigcontent text-bold text-primary">
                                {user.credits}
                            </span>
                            <p className="text-small text-silent">Solde disponible</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <nav className="profile__menu">
                <button
                    className={`text-small ${activeTab === "infos" ? "active" : ""}`}
                    onClick={() => setActiveTab("infos")}
                >
                    Infos perso.
                </button>

                <button
                    className={`text-small ${activeTab === "security" ? "active" : ""}`}
                    onClick={() => setActiveTab("security")}
                >
                    Sécurité
                </button>

                <button
                    className={`text-small ${activeTab === "bookings" ? "active" : ""}`}
                    onClick={() => setActiveTab("bookings")}
                >
                    Mes réservations
                </button>
                
                {/* { isDriver && (
                    
                )} */}

                { isDriver && (
                    <>
                        <button
                            className={`text-small ${activeTab === "vehicles" ? "active" : ""}`}
                            onClick={() => setActiveTab("vehicles")}
                        >
                            Mes véhicules
                        </button>

                        <button
                            className={`text-small ${activeTab === "drives" ? "active" : ""}`}
                            onClick={() => setActiveTab("drives")}
                        >
                            Mes trajets
                        </button>

                        <button
                            className={`text-small ${activeTab === "reviews" ? "active" : ""}`}
                            onClick={() => setActiveTab("reviews")}
                        >
                            Avis
                        </button>

                        <button
                            className={`text-small ${activeTab === "preferences" ? "active" : ""}`}
                            onClick={() => setActiveTab("preferences")}
                        >
                            Préférences
                        </button>
                    </>
                )}
            </nav>
        </div>
    )
}