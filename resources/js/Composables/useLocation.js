import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

export function useLocation() {
    const latitude = ref(null);
    const longitude = ref(null);
    const page = usePage();

    const resetLocation = () => {
        latitude.value = null;
        longitude.value = null;
    };


    const getLocation = () => {
        resetLocation();
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitude.value = position.coords.latitude;
                    longitude.value = position.coords.longitude;
                },
                (error) => {
                    alert("Gagal mendapatkan lokasi: " + error.message);
                },
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        } else {
            alert("Geolocation tidak didukung di browser ini.");
        }
    };

    const checkIn = () => {
        getLocation();
        setTimeout(() => {
            if (latitude.value && longitude.value) {
                router.post(route('attendances.checkin'), {
                    latitude: latitude.value,
                    longitude: longitude.value,
                },
                {
                    onSuccess: () => {
                        if(page.props.flash.success){
                            toast(page.props.flash.success, {
                                type: toast.TYPE.SUCCESS,
                                position: toast.POSITION.TOP_RIGHT,
                            });
                        }else{
                            toast(page.props.flash.error, {
                                type: toast.TYPE.ERROR,
                                position: toast.POSITION.TOP_RIGHT,
                            });
                        }
                        resetLocation();
                    },
                }
            );
            } else {
                alert("Tunggu sebentar, sedang mengambil lokasi...");
            }
        }, 1000);
    };

    const checkOut = () => {
        getLocation();
        setTimeout(() => {
            if (latitude.value && longitude.value) {
                router.patch(route('attendances.checkout'), {
                    latitude: latitude.value,
                    longitude: longitude.value,
                }, {
                    onSuccess: () => {
                        if(page.props.flash.success){
                            toast(page.props.flash.success, {
                                type: toast.TYPE.SUCCESS,
                                position: toast.POSITION.TOP_RIGHT,
                            });
                        }else{
                            toast(page.props.flash.error, {
                                type: toast.TYPE.ERROR,
                                position: toast.POSITION.TOP_RIGHT,
                            });
                        }
                        resetLocation();
                    },
                });
            } else {
                alert("Tunggu sebentar, sedang mengambil lokasi...");
            }
        }, 1000);
    };

    return { latitude, longitude, getLocation, checkIn, checkOut };
}

