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