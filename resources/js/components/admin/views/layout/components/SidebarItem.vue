<template>
    <router-link tag="li" class="nav-item " :to="href" :id="id">
        <a class="nav-link dropdown-toggle" @click="toggle" disabled>
            <svg-vue :icon="icon"/>
            <span>{{ name }}</span>
        </a>
        <slot/>
    </router-link>
</template>

<script>
    export default {
        name: 'sidebar-nav-item',
        props: {
            href: {
                type: String,
                default: ''
            },
            icon: {
                type: String,
                default: 'smile'
            },
            name: {
                type: String,
                default: ''
            },
            id: {
                type: String,
                default: '',
            }
        },
        methods: {
            toggle: function (e) {
                e.preventDefault();
                e.currentTarget.parentElement.classList.toggle('open');
            }
        }
    }
</script>
<style lang="scss" scoped>
    .nav-link {
        &:hover {
            svg {
                fill: #1d68a7;
            }
        }
    }

    .dropdown-toggle {
        position: relative;
        width: 200px;
        transition: padding-left .2s;

        &::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            transform: rotate(90deg);
            transition: all 0.2s;
        }

        span {
            transition: all .2s;
            position: absolute;
            left: 40px;
            display: inline-flex;
            max-width: 100%;
            min-width: 180px;
        }

    }

    .side-bar-close {

        .nav-item {
            .nav-link {
                padding-left: 25px;
            }

            span {
                left: 200px;
            }
        }

        .dropdown-toggle::after {
            display: none;
        }
    }

    .open {
        .dropdown-toggle {
            /*background: rgba(255,255,255, .1);*/
            /*border-bottom: 1px solid rgba(255,255,255,.5)*/
        }

        .dropdown-toggle::after {
            transform: rotate(0deg);
        }
    }

    @media (max-width: 991.98px) {
        .side-bar-close {

            .nav-item {
                .nav-link {
                    padding-left: 15px;
                }

                span {
                    left: 40px;
                }
            }

            .dropdown-toggle::after {
                display: block;
            }
        }
        .dropdown-toggle {
            color: rgba(255,255,255,.7);
            svg {
                fill: rgba(255,255,255,.7);
            }

            &:hover {
                color: #fff;

                svg {
                    fill: #fff;
                }
            }
        }
    }
</style>
