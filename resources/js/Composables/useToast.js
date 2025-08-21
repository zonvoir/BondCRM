import { useToast } from 'vue-toastification';
export function useCustomToast() {
    const toast = useToast();

    const showToast = (message, type = 'success') => {
        toast[type](message, { timeout: 2000 });
    };

    return {
        showToast,
    };
}
