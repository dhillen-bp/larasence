import { ref } from "vue";

export const useBadge = () => {
    const attendanceStatus = ref(["on_time", "late", "absent", "permission"]);
    const permissionTypes = ref(["leave", "permission", "sick"]);

    const getBadgeClass = (value, type = "status") => {
        if (type === "attendance") {
            switch (value) {
                case "absent":
                    return "danger";
                case "permission":
                    return "info";
                case "late":
                    return "warn";
                case "on_time":
                    return "success";
            }
        }

        if (type === "permission") {
            switch (value) {
                case "leave":
                    return "danger";
                case "permission":
                    return "info";
                case "sick":
                    return "warn";
            }
        }

        return null;
    };

    return {
        attendanceStatus,
        permissionTypes,
        getBadgeClass,
    };
};
