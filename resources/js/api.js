import axios from "axios";

const api = axios.create({
    baseURL:  import.meta.env.VITE_API_URL
    ,
    headers: {
        // "Content-Type" : "application/json",
        "Accept": "appliction/json",
        "X-Requested-With": "XMLHttpRequest",
    },
});

export default api;