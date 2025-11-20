import { Navigate, useLocation } from "react-router-dom";
import { useAuth } from "@provider/AuthContext";

export function RequireAuth({ children }: { children: React.ReactNode }) {
    const { isAuthenticated, loading } = useAuth()
    const location = useLocation()

    if (loading) {
        return null
    }

    if (!isAuthenticated) {
        return <Navigate to="/login" state={{ from: location }} replace />
    }
    
    return (
        <>
            {children}
        </>
    )
}