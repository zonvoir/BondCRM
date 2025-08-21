import '../css/app.css';
import './bootstrap';

import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';
import PrimeVue from 'primevue/config';

import { setThemeOnLoad } from '@/Composables/theme';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import Tooltip from 'primevue/tooltip';

const MyPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{indigo.50}',
            100: '{indigo.100}',
            200: '{indigo.200}',
            300: '{indigo.300}',
            400: '{indigo.400}',
            500: '{indigo.500}',
            600: '{indigo.600}',
            700: '{indigo.700}',
            800: '{indigo.800}',
            900: '{indigo.900}',
            950: '{indigo.950}',
        },
        colorScheme: {
            light: {
                formField: {
                    hoverHoverColor: '{primary.color}',
                },
                root: {
                    background: '{slate.50}',
                    color: '{surface.700}',
                },
                surface: {
                    50: '{slate.50}',
                    100: '{slate.100}',
                    200: '{slate.200}',
                    300: '{slate.300}',
                    400: '{slate.400}',
                    500: '{slate.500}',
                    600: '{slate.600}',
                    700: '{slate.700}',
                    800: '{slate.800}',
                    900: '{slate.900}',
                    950: '{slate.950}',
                },
            },
            dark: {
                formField: {
                    hoverHoverColor: '{primary.color}',
                },
                surface: {
                    50: '{gray.50}',
                    100: '{gray.100}',
                    200: '{gray.200}',
                    300: '{gray.300}',
                    400: '{gray.400}',
                    500: '{gray.500}',
                    600: '{gray.600}',
                    700: '{gray.700}',
                    800: '{gray.800}',
                    900: '{gray.900}',
                    950: '{gray.950}',
                },
            },
        },
    },
});

createInertiaApp({
    title: title => title,
    resolve: name =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: MyPreset,
                    options: {
                        darkModeSelector: '.my-app-dark',
                    },
                },
            })
            .use(Toast)
            .use(ZiggyVue)
            .directive('tooltip', Tooltip)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el);
    },
    progress: { color: '#4B5563' },
});

setThemeOnLoad();
