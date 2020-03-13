<template>
    <div class="tags-view-container">
        <div class="tags-controller tags-prev" @click="handleScroll(240)">
            <svg-vue icon="double-arrow-left"/>
        </div>
        <div class="tags-view">
            <div ref="tagsViewLayout" class="tags-view-layout">
                <ul ref="tagsWrapper" :style="{left: tagsWrapperLeft + 'px'}" class="tags-view-wrapper">
                    <router-link
                        v-for="tag in visitedViews"
                        :class="isActive(tag)?'active':''"
                        :to="{ path: tag.path, query: tag.query, fullPath: tag.fullPath }"
                        :key="tag.path"
                        tag="li"
                        ref="tag"
                        class="tags-view-item">
                        {{ tag.title }}
                        <span v-if="!isAffix(tag)" class="tag-close" @click.prevent.stop="closeTag(tag)">
                            <svg-vue icon="close"/>
                        </span>
                    </router-link>
                </ul>
            </div>
        </div>
        <div class="tags-controller tags-next" @click="handleScroll(-240)">
            <svg-vue icon="double-arro-right"/>
        </div>
    </div>
</template>
<script>
    import store from '../../../store'
    import path from 'path'

    export default {
        name: 'Tags',
        data() {
            return {
                left: 0,
                tagsWrapperLeft: 0,
                routers: store.getters.routers
            }
        },
        computed: {
            visitedViews() {
                return this.$store.state.tagsView.visitedViews
            }
        },
        watch: {
            $route() {
                this.addViewTags();
                this.moveToCurrentTag();
            }
        },
        mounted() {
            this.initTags();
            this.addViewTags();
        },
        methods: {
            isAffix(tag) {
                return tag.meta && tag.meta.affix
            },
            filterAffixTags(routes, basePath = '/') {
                let tags = [];
                routes.forEach(route => {
                    if (route.meta && route.meta.affix) {
                        const tagPath = path.resolve(basePath, route.path);
                        tags.push({
                            fullPath: tagPath,
                            path: tagPath,
                            name: route.name,
                            meta: { ...route.meta }
                        })
                    }
                    if (route.children) {
                        const tempTags = this.filterAffixTags(route.children, route.path);
                        if (tempTags.length >= 1) {
                            tags = [...tags, ...tempTags]
                        }
                    }
                });
                return tags
            },
            initTags() {
                const affixTags = this.affixTags = this.filterAffixTags(this.routers);
                for (const tag of affixTags) {
                    // Must have tag name
                    if (tag.name) {
                        this.$store.dispatch('addVisitedView', tag)
                    }
                }
            },
            handleScroll() {

            },
            isActive(route) {
                return route.path === this.$route.path
            },
            addViewTags() {
                const {name} = this.$route;
                if (name) {
                    this.$store.dispatch('addView', this.$route)
                }
                return false
            },
            /* 切换tab */
            moveToCurrentTag() {
                const tags = this.$refs.tag;
                this.$nextTick(() => {
                    for (const tag of tags) {
                        if (tag.to.path === this.$route.path) {
                            this.tabsBodyChange(tag)
                            if (tag.to.fullPath !== this.$route.fullPath) {
                                this.$store.dispatch('updateVisitedView', this.$route)
                            }
                            break
                        }
                    }
                })
            },
            closeTag(tag) {
                this.$store.dispatch('delView', tag).then(({visitedViews}) => {
                    if (this.isActive(tag)) {
                        const latestView = visitedViews.slice(-1)[0];
                        if (latestView) {
                            this.$router.push(latestView)
                        } else {
                            this.$router.push({path: '/'})
                            this.addViewTags()
                        }
                    }
                })
            },
            /* 左右滚动 */
            handleScroll(offset) {
                const $tagsWrapper = this.$refs.tagsWrapper
                const $tagsWrapperWidth = $tagsWrapper.offsetWidth
                const $tagsWrapperScrollWidth = $tagsWrapper.scrollWidth
                if (offset > 0) {
                    this.tagsWrapperLeft = Math.min(0, this.tagsWrapperLeft + offset)
                } else {
                    if ($tagsWrapperWidth < $tagsWrapperScrollWidth) {
                        if (this.tagsWrapperLeft < -($tagsWrapperScrollWidth - $tagsWrapperWidth)) {
                            this.tagsWrapperLeft = this.tagsWrapperLeft
                        } else {
                            this.tagsWrapperLeft = Math.max(this.tagsWrapperLeft + offset, $tagsWrapperWidth - $tagsWrapperScrollWidth)
                        }
                    }
                }
            },
            /* 切换tabs定位 */
            tabsBodyChange(currentTag) {
                const $tagsWrapper = this.$refs.tagsWrapper, $tagsWrapperWidth = $tagsWrapper.offsetWidth,
                    $tagsWrapperScrollWidth = $tagsWrapper.scrollWidth, tags = this.$refs.tag;

                let firstTag = null,lastTag = null,prevTag = null,nextTag = null;
                if (tags.length > 0) {
                    firstTag = tags[0]
                    lastTag = tags[tags.length - 1]
                }

                for (let i = 0; i < tags.length; i++) {
                    if (tags[i] === currentTag) {
                        if (i === 0) {
                            nextTag = tags[i].length > 1 && tags[i + 1]
                        } else if (i === tags.length - 1) {
                            prevTag = tags[i].length > 1 && tags[i - 1]
                        } else {
                            prevTag = tags[i - 1]
                            nextTag = tags[i + 1]
                        }
                        break
                    }
                }
                if (firstTag === currentTag) {
                    this.tagsWrapperLeft = 0
                } else if (lastTag === currentTag) {
                    this.tagsWrapperLeft = $tagsWrapperWidth - $tagsWrapperScrollWidth
                } else {
                    const beforePrevTag = prevTag.$el.offsetLeft + prevTag.$el.offsetWidth
                    const afterNextTag = nextTag.$el.offsetLeft
                    if (afterNextTag > $tagsWrapperWidth + Math.abs(this.tagsWrapperLeft)) {
                        this.tagsWrapperLeft = $tagsWrapperWidth - afterNextTag
                    } else if (beforePrevTag < Math.abs(this.tagsWrapperLeft)) {
                        this.tagsWrapperLeft = -beforePrevTag
                    }
                }
            }
        }
    }
</script>
<style lang="scss" scoped>
    .tags-view-container {
        height: 35px;
        width: 100%;
        position: relative;
        box-shadow: 3px 1px 3px 0 rgba(0, 0, 0, .12), 0 0 3px 0 rgba(0, 0, 0, .04);

        .tags-controller {
            width: 40px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            cursor: pointer;
            top: 0;
            z-index: 10;
            position: absolute;
            /*background: rgba(255,255,255,.5);*/
            border-left: 1px solid #aaaaaa;
            transition: all .3s;

            &.tags-prev {
                left: 0;
                border-left: none;
                border-right: 1px solid #aaaaaa;
            }

            &.tags-next {
                right: 0;
                border-left: 1px solid #aaaaaa;
                border-right: none;
            }
        }

        .tag-close {
            margin-left: 5px;
            width: 12px;
            height: 12px;
            margin-top: -2px;
            &:hover {
                svg {
                    fill: #e3342f!important;
                    background: rgba(0,0,0,.3);
                    border-radius: 50%;
                }
            }
        }

        .tags-view {
            overflow: hidden;
            position: absolute;
            left: 40px;
            right: 80px;
            white-space: nowrap;
            display: inline-block;

            .tags-view-layout {
                width: 100%;
            }

            .tags-view-wrapper {
                padding: 0;
                height: 100%;
                margin: 0;
                position: relative;

                .tags-view-item {
                    display: inline-block;
                    position: relative;
                    cursor: pointer;
                    height: 100%;
                    line-height: 34px;
                    color: #495060;
                    padding: 0 8px;
                    font-size: 12px;
                    border-right: 1px solid #aaaaaa;

                    &.active {
                        color: #3490dc;
                        svg{
                            fill:#3490dc;
                        }
                    }
                    &::before {
                        content: '';
                        background: #1d68a7;
                        display: inline-block;
                        width: 0;
                        height: 2px;
                        position: absolute;
                        left: 0;
                        transition: all .3s;
                    }

                    &:hover {
                        &::before {
                            width: 100%;
                            height: 2px;
                        }
                    }
                }
            }
        }
    }
</style>
