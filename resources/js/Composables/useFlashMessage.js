import { router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, watch } from 'vue';
import { useCustomToast } from './useToast';

export function useFlashMessages() {
    const { showToast } = useCustomToast();
    const page = usePage();

    const flash = computed(() => page.props.flash);

    const showFlashMessages = () => {
        if (flash.value.message) {
            showToast(flash.value.message, flash.value.type || 'success');
            page.props.flash = {};
        }
    };

    watch(
        () => page.props.flash,
        newFlash => {
            if (newFlash.message) {
                showFlashMessages();
            }
        },
        { deep: true }
    );

    onMounted(() => {
        router.on('finish', () => {
            page.props.flash = {};
        });
    });

    return {
        showFlashMessages,
    };
}
