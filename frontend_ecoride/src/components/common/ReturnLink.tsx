import type { JSX } from "react"

import { Link } from "react-router-dom"

import { CornerDownLeft } from "lucide-react"

export type ReturnLinkProps = {
    text?: string
    to?: string
}

export function ReturnLink({
    text,
    to
}: ReturnLinkProps): JSX.Element {
    return (
        <>
            <Link
                to={to ?? ".." }
                relative="path"
                className="return-link text-small text-silent"
            >
                <div className="return-link__item">
                    <CornerDownLeft size={20} />
                    <p>{text ? text : "Retour"}</p>
                </div>
            </Link>
        </>
    )
}