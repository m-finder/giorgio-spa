import 'izitoast/dist/css/iziToast.min.css'
import alert from 'izitoast'

export default {
    error: (message, title = 'Error') => {
        return alert.error({
            title: title,
            message: message,
            position: 'topCenter',
            timeout: 10000,
        });
    },
    success: (message, title = 'Success') => {
        return alert.success({
            title: title,
            message: message,
            position: 'topCenter',
            timeout: 10000,
        });
    }
};
