import axios from "axios";
import { useNotificationStore } from "../components/shared/notification/notificationStore";

// Add a request interceptor
// axios.interceptors.request.use(
//     function (config)
//     {

//         //console.log(config.headers);

//         if(config.method == "post"||config.method == "put"||config.method == "delete"){
//             const notifcationStore = useNotificationStore()
//             notifcationStore.pushNotification({
//                 'message':"Demo version doesn't allow to do this",
//                 'type'   :'error',
//                 'time':3000
//             })
//            throw new axios.Cancel('Operation canceled by the user.');
//         }

//         return config;
//     },
//     function (error) {
//     return Promise.reject(error);
//     }
// );

//
axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
};
