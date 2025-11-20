import { useState, useRef, useEffect } from "react"
import { Link, useLocation } from "react-router-dom"
import { Leaf, Menu, X } from "lucide-react"
import { NavLinks } from "@components/common/Navbar/NavLinks"
import { useAuth } from "@provider/AuthContext"

export function NavBar() {
    const location = useLocation()

    const [isOpen, setIsOpen] = useState<boolean>(false)
    const { isAuthenticated } = useAuth()

    const menuRef = useRef<HTMLDivElement | null>(null)
    const buttonRef = useRef<HTMLButtonElement | null>(null)


    const toggleMenu = () => setIsOpen((open) => !open)
    const closeMenu = () => setIsOpen(false)

    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (!isOpen) return
            const target = event.target as Node

            if (
                menuRef.current &&
                !menuRef.current.contains(target) &&
                buttonRef.current &&
                !buttonRef.current.contains(target)
            ) {
                closeMenu()
            }
        }

        document.addEventListener('mousedown', handleClickOutside)
        return () => {
            document.removeEventListener('mousedown', handleClickOutside)
        }
    }, [isOpen])

    useEffect(() => {
        if (isOpen) {
            closeMenu()
        }
    }, [location.pathname])

    return (
        <header className="navbar">
            <div className="navbar__container" ref={menuRef}>
                <Link to="/" className="navbar__brand" onClick={closeMenu}>
                    <Leaf className="navbar__icon" />
                    <span className="navbar__title text-content">Ecoride</span>
                </Link>


                <div id="navbar-links" className={`navbar__links ${isOpen ? "open" : ""}`}>
                    <NavLinks 
                        isAuthenticated={isAuthenticated}
                        onItemClick={closeMenu}
                        className={isOpen ? "open" : ""}
                    />
                </div>

                <button
                    ref={buttonRef}
                    className="navbar__burger"
                    onClick={toggleMenu}
                    aria-label={isOpen ? "Fermer le menu" : "Ouvrir le menu"}
                    aria-expanded={isOpen}
                    aria-controls="navbar-links"
                >
                    {isOpen  ? <X className="navbar__burger-icon"/> : <Menu className="navbar__burger-icon"/>}
                </button>

                
            </div>
        </header>
    )

}