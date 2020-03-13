const storage = window.localStorage;
const sessionStorage = window.sessionStorage;

const table = 'laravel-storage';

export default {
    set: function (settings) {
        if (!window.JSON || !window.JSON.parse) return

        settings = typeof settings === 'object'
            ? settings
            : {key: settings};

        const data = storage[table] ? JSON.parse(storage[table]) : {};

        if ('value' in settings) {
            data[settings.key] = settings.value
        } else {
            Object.keys(settings).forEach(function (item, index) {
                data[item] = settings[item]
            })
        }
        storage[table] = JSON.stringify(data)
    },
    get: function (key) {
        let data = JSON.parse(storage.getItem(table));
        try {
            return data[key]
        } catch (e) {
            return null
        }
    },
    remove: function (key = null) {
        if (key === null) {
            storage.removeItem(table)
        } else {
            const data = JSON.parse(storage[table]);
            delete data[key]
            storage[table] = JSON.stringify(data);
        }
    },
    sessionSet: function (settings) {
        if (!window.JSON || !window.JSON.parse) return;

        settings = typeof settings === 'object'
            ? settings
            : {key: settings};

        const data = sessionStorage[table] ? JSON.parse(sessionStorage[table]) : {};

        if ('value' in settings) {
            data[settings.key] = settings.value
        } else {
            Object.keys(settings).forEach(function (item, index) {
                data[item] = settings[item]
            })
        }
        sessionStorage[table] = JSON.stringify(data)
    },
    sessionGet: function (key) {
        let data = JSON.parse(sessionStorage.getItem(table));
        try {
            return data[key]
        } catch (e) {
            return null
        }
    },
    sessionRemove: function (key = null) {
        if (key === null) {
            sessionStorage.removeItem(table)
        } else {
            const data = JSON.parse(sessionStorage[table]);
            delete data[key]
        }
    }
}
