import axios from "axios";

export default async function deleteUploadedFile(fileIDs) {
    return new Promise((resolve, reject) => {
        axios
            .delete(`/api/upload/${fileIDs}`)
            .then((response) => {
                resolve(fileIDs);
            })
            .catch((error) => {
                reject(error);
            });
    });
}
