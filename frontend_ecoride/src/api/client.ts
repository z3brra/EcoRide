import axios from "axios"

export const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
    },
    withCredentials: true,
})

export const apiFormClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    headers: {
        'Content-Type': 'multipart/form-data',
    },
    withCredentials: true
})

function registerSettlementInterceptor(client: typeof apiClient) {
    client.interceptors.response.use(
        (response) => response,
        (error) => {
            const status = error.response?.status

            if (status === 423) {
                const uuid = error.response?.data?.driveUuid

                if (uuid) {
                    window.dispatchEvent(
                        new CustomEvent("settlement-lock", {
                            detail: uuid,
                        })
                    )
                }
            }
            return Promise.reject(error)
        }
    )
}

registerSettlementInterceptor(apiClient)
registerSettlementInterceptor(apiFormClient)