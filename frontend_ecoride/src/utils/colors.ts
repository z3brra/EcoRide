export const vehicleColors = [
    { label: "Noir", value: "BLACK" },
    { label: "Gris", value: "GREY" },
    { label: "Blanc", value: "WHITE" },
    { label: "Marron", value: "BROWN" },
    { label: "Rouge", value: "RED" },
    { label: "Orange", value: "ORANGE" },
    { label: "Jaune", value: "YELLOW" },
    { label: "Vert", value: "GREEN" },
    { label: "Bleu", value: "BLUE" },
    { label: "Violet", value: "PURPLE" },
    { label: "Rose", value: "PINK" },
]

export function formatColor(color: string) {
    return (
        vehicleColors.find((c) => c.value === color) ?? { label: "Inconnue", value: "BLACK" }
    )
}