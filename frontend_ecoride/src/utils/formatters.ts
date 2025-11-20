import type { ReservationStatus } from "@models/status"

export function formatDate(dateString: string | Date): string {
    const newDate = new Date(dateString)
    if (isNaN(newDate.getTime())) {
        return String(dateString)
    }

    const day = String(newDate.getDate()).padStart(2, "0")
    const monthAbbreviation = [
        "Janv.",
        "Fév.",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juil.",
        "Août",
        "Sept.",
        "Oct.",
        "Nov.",
        "Déc."
    ]
    const month = monthAbbreviation[newDate.getMonth()]
    const year = newDate.getFullYear()

    return `${day} ${month} ${year}`
}

export function formatTime(dateString: string | Date): string {
    const newDate = new Date(dateString)
    if (isNaN(newDate.getTime())) {
        return String(dateString)
    }

    const hours = String(newDate.getHours()).padStart(2, "0")
    const minutes = String(newDate.getMinutes()).padStart(2, "0")

    return `${hours}h${minutes}`
}

export function getStatusLabel(status: ReservationStatus) {
    switch (status) {
        case "open":
            return { text: "En attente", className: "status--open"}
        case "in_progress":
            return { text: "En cours", className: "status--in-progress"}
        case "finished":
            return { text: "Terminé", className: "status--finished"}
        case "cancelled": 
            return { text: "Annulé", className: "status--cancelled"}
        default:
            return { text: "Inconnu", className: ""}
    }
}

export function formatColor(color: string) {
    switch (color) {
        case "BLACK":
            return { color: "Noir"}

        case "GREY":
            return { color: "Gris"}

        case "WHITE":
            return { color: "Blanc"}

        case "BROWN":
            return { color: "Marron"}

        case "RED":
            return { color: "Rouge"}

        case "ORANGE":
            return { color: "Orange"}

        case "YELLOW":
            return { color: "Jaune"}

        case "GREEN":
            return { color: "Vert"}

        case "BLUE":
            return { color: "Bleu"}

        case "PURPLE":
            return { color: "Violet"}

        case "PINK":
            return { color: "Rose"}
        default:
            return { color: "Inconnue"}
    }
}