import storage from '../../utils/storage'

const state = {
    sidebar: {
        opened: !+storage.get('sidebarStatus'),
        mobileOpened: !!+storage.get('mobileOpened'),
    },
    theme: storage.get('theme') || 'bd-light-blue'
}

const mutations = {
    TOGGLE_SIDEBAR: state => {
        state.sidebar.opened = !state.sidebar.opened;
        if (state.sidebar.opened) {
            storage.set({'sidebarStatus': 0})
        } else {
            storage.set({'sidebarStatus': 1})
        }
    },
    TOGGLE_MOBILE_SIDEBAR: state => {
        state.sidebar.mobileOpened = !state.sidebar.mobileOpened;
        if (state.sidebar.mobileOpened) {
            storage.set({'mobileOpened': 0});
        } else {
            storage.set({'mobileOpened': 1});
        }
    },
    CLOSE_MOBILE_SIDEBAR: state => {
        state.sidebar.mobileOpened = 0;
        storage.set({'mobileOpened': 0});
    },
    CLOSE_SIDEBAR: (state, withoutAnimation) => {
        storage.set({'sidebarStatus': 1});
        state.sidebar.opened = false
    },
    OPEN_SIDEBAR: (state) => {
        storage.set({'sidebarStatus': 0});
        state.sidebar.opened = true
    },
    CHANGE_THEME: (state, theme) => {
        storage.set({'theme': theme});
        state.theme = theme
    }
};

const actions = {
    toggleSideBar({commit}) {
        commit('TOGGLE_SIDEBAR')
    },
    toggleMobileSideBar({commit}) {
        commit('TOGGLE_MOBILE_SIDEBAR')
    },
    closeMobileSideBar({commit}) {
        commit('CLOSE_MOBILE_SIDEBAR')
    },
    openSideBar({commit}) {
        commit('OPEN_SIDEBAR')
    },
    changeTheme({commit}, theme) {
        commit('CHANGE_THEME', theme)
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
}
