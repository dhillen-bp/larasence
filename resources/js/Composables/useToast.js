import { toast } from "vue3-toastify";

export function showToastSuccess(message) {
    toast(message, {
        type: toast.TYPE.SUCCESS,
        position: toast.POSITION.TOP_RIGHT,
    });
}

export function showToastError(message ) {
    toast(message, {
        type: toast.TYPE.ERROR,
        position: toast.POSITION.TOP_RIGHT,
    });
}
