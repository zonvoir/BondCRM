import { computed } from 'vue';

/**
 * useSelectMapper
 *
 * @param {Ref<Array|Object>|Array|Object} data - Array or object or ref of them
 * @param {Boolean} isArray - Whether the input is array or single object
 * @returns {Object} - mappedData (either array or single object)
 */
export function useSelectMapper(data, isArray = true) {
    const isRef = data && data.value !== undefined;

    const mappedData = computed(() => {
        const rawData = isRef ? data.value : data;

        if (!rawData) return isArray ? [] : null;

        // Handle array of objects
        if (isArray) {
            if (!Array.isArray(rawData)) return [];
            return rawData.map(item => ({
                name: item.name,
                code: item.id,
            }));
        }

        // Handle single object
        return {
            name: rawData.name,
            code: rawData.id,
        };
    });

    return { mappedData };
}
