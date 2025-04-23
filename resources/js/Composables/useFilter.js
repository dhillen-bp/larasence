import { ref } from "vue";
import { FilterMatchMode } from "@primevue/core/api";

export const useFilters = () => {
    const filters = ref({});

    const initPermissionFilters = () => {
        filters.value = {
            global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            "user.name": { value: null, matchMode: FilterMatchMode.CONTAINS },
            type: { value: null, matchMode: FilterMatchMode.EQUALS },
            start_date: { value: null, matchMode: FilterMatchMode.DATE_IS },
            end_date: { value: null, matchMode: FilterMatchMode.DATE_IS },
            is_approved: { value: null, matchMode: FilterMatchMode.EQUALS },
        };
    };

    const initAttendanceFilters = () => {
        filters.value = {
            global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            "user.name": { value: null, matchMode: FilterMatchMode.CONTAINS },
            status: { value: null, matchMode: FilterMatchMode.EQUALS },
            check_in: { value: null, matchMode: FilterMatchMode.DATE_IS },
            check_out: { value: null, matchMode: FilterMatchMode.DATE_IS },
        };
    };

    const initEmployeeFilters = () => {
        filters.value = {
            global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
            email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        };
    };

    const clearFilter = (initFn) => {
        if (typeof initFn === "function") {
            initFn();
        }
    };

    return {
        filters,
        initPermissionFilters,
        initAttendanceFilters,
        initEmployeeFilters,
        clearFilter,
    };
};
