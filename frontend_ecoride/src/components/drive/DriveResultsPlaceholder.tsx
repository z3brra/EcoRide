import type { JSX } from "react"
import { MapPin, MapPinX } from "lucide-react"

export type DriveResultsPlaceholderProps = {
    noResults?: boolean
    className?: string
}

export function DriveResultsPlaceholder({
    noResults = false,
    className = "",
}: DriveResultsPlaceholderProps): JSX.Element {
    const Icon = noResults ? MapPinX : MapPin
    
    const title = noResults
        ? "Aucun résultat"
        : "Aucun recherche pour le moment"

    const description = noResults
        ? "Aucun itinéraire n'est disponible, veuillez élargir vos critères de sélection."
        : "Utilisez la barre de recherche ci-dessus pour trouver les itinéraires de covoiturage disponibles."

    return (
        <div className={`drive-results-placeholder ${className}`}>
            <div className="drive-results-placeholder__icon text-primary">
                <Icon size={60} />
            </div>
            <h3 className="drive-results-placeholder__title text-subtitle text-primary">
                {title}
            </h3>
            <p className="drive-results-placeholder__description text-content text-silent">
                {description}
            </p>
        </div>
    )
}