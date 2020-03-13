const getters = {
    sidebar: state => state.app.sidebar,
    theme: state => state.app.theme,
    visitedViews: state => state.tagsView.visitedViews,
    cachedViews: state => state.tagsView.cachedViews,
    routers: state => state.permission.routers,
    addRouters: state => state.permission.addRouters,
    elements: state => state.permission.elements
}
export default getters
