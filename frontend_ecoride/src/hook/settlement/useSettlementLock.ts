import { useEffect } from "react"

export function useSettlementLock(
    onTrigger: (
        uuid: string
    ) => void) {
    useEffect(() => {
        const handler = (event: any) => {
            if (event.detail) onTrigger(event.detail)
        }

        window.addEventListener("settlement-lock", handler)
        return () => window.removeEventListener("settlement-lock", handler)
    }, [onTrigger])
}
