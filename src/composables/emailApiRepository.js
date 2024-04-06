import axios from "axios";

const Email = () => {

    const fetchEmails = async (params, url) => {
        try {
            url = url ?? `/email`;
            const response = await axios.get(url,  { params: params });
            return response;
        } catch (error) {
            console.log(error);
        }
    };

    const createEmail = async (payload) => {
        try {
          const response = await axios.post(`/email/create`, payload);
          return response;
        } catch (err) {
          return err;
        }
    };

    const updateEmail = async (email_id, payload) => {
        try {
          const response = await axios.put(`/email/update/${email_id}`, payload);
          return response;
        } catch (err) {
          return err;
        }
    };
    
    const deleteEmail = async (email_id) => {
        try {
          const response = await axios.delete(`/email/${email_id}`);
          return response;
        } catch (error) {
          console.log(error);
        }
    };
    
    const uploadEmails = async (payload) => {
      try {
        const response = await axios.post(`/email/upload`, payload);
        return response;
      } catch (err) {
        return err;
      }
    };

    const getCount = async (params) => {
      try {
        const response = await axios.get(`/email/counts`, {params: params});
        return response;
      } catch (err) {
        return err;
      }
    };

    return {
        uploadEmails,
        fetchEmails,
        createEmail,
        updateEmail,
        deleteEmail,
        getCount,
    }
};

export default Email;