import { useState, useCallback } from 'react';

/**
 * Custom hook để quản lý trạng thái API calls
 * @param {Function} apiFunction - Function API cần gọi
 * @returns {Object} - Trạng thái và function để gọi API
 */
export const useApi = (apiFunction) => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const execute = useCallback(async (...args) => {
        try {
            setLoading(true);
            setError(null);
            const result = await apiFunction(...args);
            setData(result);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [apiFunction]);

    const reset = useCallback(() => {
        setData(null);
        setLoading(false);
        setError(null);
    }, []);

    return {
        data,
        loading,
        error,
        execute,
        reset
    };
};

/**
 * Hook để quản lý danh sách dữ liệu với pagination
 * @param {Function} fetchFunction - Function để fetch dữ liệu
 * @returns {Object} - Trạng thái và functions
 */
export const useApiList = (fetchFunction) => {
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0
    });

    const fetchData = useCallback(async (params = {}) => {
        try {
            setLoading(true);
            setError(null);
            const result = await fetchFunction(params);

            if (result.data) {
                setItems(result.data);
                if (result.meta) {
                    setPagination(result.meta);
                }
            } else {
                setItems(Array.isArray(result) ? result : []);
            }

            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [fetchFunction]);

    const refresh = useCallback(() => {
        return fetchData();
    }, [fetchData]);

    const reset = useCallback(() => {
        setItems([]);
        setLoading(false);
        setError(null);
        setPagination({
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0
        });
    }, []);

    return {
        items,
        loading,
        error,
        pagination,
        fetchData,
        refresh,
        reset
    };
};

/**
 * Hook để quản lý form submission
 * @param {Function} submitFunction - Function để submit form
 * @returns {Object} - Trạng thái và function submit
 */
export const useApiForm = (submitFunction) => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const submit = useCallback(async (formData) => {
        try {
            setLoading(true);
            setError(null);
            setSuccess(false);

            const result = await submitFunction(formData);
            setSuccess(true);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [submitFunction]);

    const reset = useCallback(() => {
        setLoading(false);
        setError(null);
        setSuccess(false);
    }, []);

    return {
        loading,
        error,
        success,
        submit,
        reset
    };
};

/**
 * Hook để quản lý CRUD operations
 * @param {Object} apiService - Object chứa các methods CRUD
 * @returns {Object} - Các functions CRUD và trạng thái
 */
export const useCrud = (apiService) => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const create = useCallback(async (data) => {
        try {
            setLoading(true);
            setError(null);
            const result = await apiService.create(data);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [apiService]);

    const update = useCallback(async (id, data) => {
        try {
            setLoading(true);
            setError(null);
            const result = await apiService.update(id, data);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [apiService]);

    const remove = useCallback(async (id) => {
        try {
            setLoading(true);
            setError(null);
            const result = await apiService.delete(id);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [apiService]);

    const get = useCallback(async (id) => {
        try {
            setLoading(true);
            setError(null);
            const result = await apiService.get(id);
            return result;
        } catch (err) {
            setError(err);
            throw err;
        } finally {
            setLoading(false);
        }
    }, [apiService]);

    const reset = useCallback(() => {
        setLoading(false);
        setError(null);
    }, []);

    return {
        loading,
        error,
        create,
        update,
        remove,
        get,
        reset
    };
};
