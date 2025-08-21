import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePermissions() {
    const permissions = computed(
        () => usePage().props.auth?.user?.permissions || []
    );

    const hasPermission = permission => {
        return permissions.value.includes(permission);
    };

    const hasAnyPermission = (requiredPermissions = []) => {
        return requiredPermissions.some(p => permissions.value.includes(p));
    };

    const hasAllPermissions = (requiredPermissions = []) => {
        return requiredPermissions.every(p => permissions.value.includes(p));
    };

    return {
        permissions,
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
    };
}
