import axios from "axios";

const Auth = () => {

    const login = async (payload) => {
        try {
            const response = await axios.post(`/user/login`, payload);
            return response;
        } catch (err) {
            return err;
        }
    };
  
    const forgotPassword = async (payload) => {
        try {
            let response = await axios.post(`/user/forgot-password`, payload);
            return response;
        } catch (err) {
            return err.response;
        }
    };
  
    const resetPassword = async (payload) => {
        try {
            let response = await axios.post(`/user/reset-password`, payload);
            return response;
        } catch (err) {
            return err.response;
        }
    };

    const logout = async () => {
        try {
            const response = await axios.post(`/user/logout`);
            if(response.status == 200){
                localStorage.removeItem('token')
            }
            return response;
        } catch (err) {
            return err;
        }
    };

    return {
        login,
        resetPassword,
        forgotPassword,
        logout
    }
};

export default Auth;