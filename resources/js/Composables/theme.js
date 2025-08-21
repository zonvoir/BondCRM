import { computed, ref } from 'vue';

const isDarkTheme = ref(false);

function setThemeOnLoad() {
    if (
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('my-app-dark');
        document.documentElement.classList.add('dark');
        isDarkTheme.value = true;
    } else {
        document.documentElement.classList.remove('my-app-dark');
        document.documentElement.classList.remove('dark');
        isDarkTheme.value = false;
    }
}

function switchTheme() {
    if (isDarkTheme.value) {
        document.documentElement.classList.remove('my-app-dark');
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
        isDarkTheme.value = false;
    } else {
        document.documentElement.classList.add('my-app-dark');
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
        isDarkTheme.value = true;
    }
}

const isDark = computed(() => isDarkTheme.value);

export { isDark, setThemeOnLoad, switchTheme };
