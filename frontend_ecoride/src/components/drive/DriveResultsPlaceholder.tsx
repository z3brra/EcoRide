import type { JSX } from "react"
import { MapPin, MapPinX, Loader } from "lucide-react"

export type DriveResultsPlaceholderProps = {
    noResults?: boolean
    isLoading?: boolean
    className?: string
}

export function DriveResultsPlaceholder({
    noResults = false,
    isLoading = false,
    className = "",
}: DriveResultsPlaceholderProps): JSX.Element {
    const Icon = isLoading ? Loader : noResults ? MapPinX : MapPin
    
    const title = isLoading
        ? "Recherche en cours..."
        : noResults
        ? "Aucun résultat"
        : "Aucun recherche pour le moment"

    const description = isLoading
        ? "Nous consultons les trajets disponibles. Merci de patienter."
        : noResults
        ? "Aucun itinéraire n'est disponible, veuillez élargir vos critères de sélection."
        : "Utilisez la barre de recherche ci-dessus pour trouver les itinéraires de covoiturage disponibles."

    return (
        <div className={`drive-results-placeholder ${className}`}>
            <div className={`drive-results-placeholder__icon text-primary ${isLoading ? "is-loading" : ""}`}>
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