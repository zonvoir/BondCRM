import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useRoles() {
    const roles = computed(() => {
        const roles = usePage().props.auth?.roles;
        return Array.isArray(roles) ? roles : [];
    });

    const hasRole = role => roles.value.includes(role);

    const hasAnyRole = (requiredRoles = []) =>
        requiredRoles.some(role => roles.value.includes(role));

    const hasAllRoles = (requiredRoles = []) =>
        requiredRoles.every(role => roles.value.includes(role));

    return {
        roles,
        hasRole,
        hasAnyRole,
        hasAllRoles,
    };
}
