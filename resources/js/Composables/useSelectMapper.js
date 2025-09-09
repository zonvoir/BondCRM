import { computed } from 'vue';

/**
 * useSelectMapper
 *
 * @param {Ref<Array|Object>|Array|Object} data - Array or object or ref of them
 * @param {Boolean} isArray - Whether the input is array or single object
 * @param {Object} fieldMapping - Custom field mapping with default fallbacks
 * @param  fieldMapping.nameField - Field to map as 'name' (default: 'name')
 * @param  fieldMapping.codeField - Field to map as 'code' (default: 'id')
 * @returns {Object} - mappedData (either array or single object)
 */
export function useSelectMapper(data, isArray = true, fieldMapping = {}) {
    const isRef = data && data.value !== undefined;

    // Default field mappings
    const nameField = fieldMapping?.nameField || 'name';
    const codeField = fieldMapping?.codeField || 'id';

    const mappedData = computed(() => {
        const rawData = isRef ? data.value : data;

        if (!rawData) return isArray ? [] : null;

        // Handle array of objects
        if (isArray) {
            if (!Array.isArray(rawData)) return [];
            return rawData.map(item => ({
                name: item[nameField],
                code: item[codeField],
            }));
        }

        // Handle single object
        return {
            name: rawData[nameField],
            code: rawData[codeField],
        };
    });

    return { mappedData };
}
