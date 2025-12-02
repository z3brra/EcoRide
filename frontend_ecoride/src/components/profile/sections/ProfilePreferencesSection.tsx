import { useState, type JSX } from "react"

import { Card } from "@components/common/Card/Card"
import { CardContent } from "@components/common/Card/CardContent"

import { Switch } from "@components/form/Switch"
import { PlusCircle, X } from "lucide-react"
import { Input } from "@components/form/Input"
import { Button } from "@components/form/Button"
import { usePreference } from "@hook/preference/usePreference"
import { MessageBox } from "@components/common/MessageBox/MessageBox"

type CustomPreferenceTagProps = {
    label: string
    onRemove: () => void
}

function CustomPreferenceTag({
    label,
    onRemove
}: CustomPreferenceTagProps): JSX.Element {
    return (
        <div className="pref-tag">
            <span className="pref-tag__text text-small">
                {label}
            </span>

            <button
                type="button"
                className="pref-tag__remove"
                onClick={onRemove}
            >
                <X size={16} />
            </button>
        </div>
    )
}


export function ProfilePreferencesSection(): JSX.Element {
    const [customPrefInput, setCustomPrefInput] = useState<string>("")

    const {
        prefs,
        loading,
        error,
        refresh,
        saveFixedprefs,
        addCustomPref,
        removeCustomPref,
        setError,
    } = usePreference()

    const handleAddCustom = async () => {
        const trimmed = customPrefInput.trim()
        if (!trimmed) {
            return
        }
        await addCustomPref(trimmed)
        setCustomPrefInput("")
        refresh()
    }

    const handleRemoveCustom = async (uuid: string) => {
        await removeCustomPref(uuid)
        console.log(uuid)
        refresh()
    }

    return (
        <>
            { error && (
                <MessageBox variant="error" message={error} onClose={() => setError(null)} />
            )}

            <div className="profile__preferences">
                <Card>
                    <CardContent gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Préférences fixes
                        </h3>
                        <p className="text-small text-silent text-left">
                            Définissez vos préférences de conduite standard.
                        </p>

                        <div className="pref-box">
                            <div className="pref-box__left">
                                <p className="text-bigcontent text-primary text-left">
                                    Autoriser les animaux
                                </p>
                                <p className="text-small text-silent text-left">
                                    Les passagers peuvent apporter leurs animaux
                                </p>
                            </div>

                            <Switch
                                checked={prefs?.animals ?? false}
                                onChange={(val) => prefs && saveFixedprefs(val, prefs.smoke)}
                            />
                        </div>

                        <div className="pref-box">
                            <div className="pref-box__left">
                                <p className="text-bigcontent text-primary text-left">
                                    Autoriser la cigarette
                                </p>
                                <p className="text-small text-silent text-left">
                                    Permettre aux passagers de fumer.
                                </p>
                            </div>

                            <Switch
                                checked={prefs?.smoke ?? false}
                                onChange={(val) => prefs && saveFixedprefs(prefs.animals, val)}
                            />
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent gap={1}>
                        <h3 className="text-subtitle text-primary text-left">
                            Préférences personalisées
                        </h3>
                        <p className="text-small text-silent text-left">
                            Ajoutez vos préférences de trajet.
                        </p>

                        <div className="pref-custom__input">
                            <div className="pref-custom__input-field">
                                <Input
                                    label="Nouvelle préférence"
                                    placeholder="Ex : J'aime rouler fenêtre ouvertes"
                                    value={customPrefInput}
                                    onChange={(event: React.ChangeEvent<HTMLInputElement>) => setCustomPrefInput(event.currentTarget.value)}
                                />
                            </div>
                            <div className="pref-custom__input-button">
                                <Button
                                    variant="primary"
                                    icon={<PlusCircle size={20} />}
                                    disabled={loading}
                                    onClick={handleAddCustom}
                                >
                                    Ajouter
                                </Button>
                            </div>
                        </div>

                        <div className="pref-custom__list">
                            { (prefs?.customPreferences?.length ?? 0) === 0 && (
                                <p className="text-small text-silent">
                                    Aucun préférence personnalisée.
                                </p>
                            )}

                            { (prefs?.customPreferences ?? []).map((pref) => (
                                <CustomPreferenceTag
                                    key={pref.uuid}
                                    label={pref.label}
                                    onRemove={() => handleRemoveCustom(pref.uuid)}
                                />
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    )
}