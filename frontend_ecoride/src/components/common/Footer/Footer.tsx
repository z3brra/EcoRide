import type { JSX } from "react"
import { Section } from "@components/common/Section/Section"

import { Leaf } from "lucide-react"

export function Footer(): JSX.Element {
    return (
        <Section id="footer">
            <div className="footer">
                <div className="footer__container-top">
                    <div className="footer-brand">
                        <Leaf className="footer-brand__icon" />
                        <span className="footer-brand__title text-bigcontent text-bold">Ecoride</span>
                    </div>

                    <div className="footer-links">
                        <p className="text-small text-silent">contact</p>
                        <p className="text-small text-silent">legal</p>
                    </div>
                </div>

                <div className="footer__container-down">
                    <div className="footer-mention">
                        <p className="text-small text-silent">© 2025 Ecoride. Tous droits résevés.</p>
                    </div>
                </div>
            </div>
        </Section>
    )
}