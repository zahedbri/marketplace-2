<template>
    <dom-root v-if="root">
        <div ref="popper">
            <slot/>
        </div>
    </dom-root>
    <div v-else ref="popper">
        <slot/>
    </div>
</template>

<script>
    import Popper from 'popper.js';
    import DomRoot from 'JS/components/helpers/dom-root.vue';

    export default {
        name: 'popper',
        components: {
            DomRoot
        },
        props: {
            placement: {
                type: String,
                default: 'bottom'
            },
            element: {
                required: true
            },
            root: {
                type: Boolean,
                default: false
            },
            offset: {
                default: 0
            },
            boundariesElement: {
                default: 'viewport'
            }
        },
        data: () => ({
            /** @type {object | null} */
            popper: null
        }),
        watch: {
            element(el, oldEl) {
                if (el !== oldEl) {
                    this.hide();
                    this.show();
                }
            }
        },
        methods: {
            show() {
                if (!this.popper && this.element) {
                    //@ts-ignore
                    this.popper = new Popper(this.element, this.$refs.popper, {
                        placement: this.placement,
                        modifiers: {
                            offset: {
                                offset: this.offset
                            },
                            preventOverflow: {
                                boundariesElement: this.boundariesElement
                            }
                        }
                    });
                }
            },
            hide() {
                if (this.popper) {
                    this.popper.destroy();
                    this.popper = null;
                }
            }
        },
        mounted() {
            this.show();
        },
        activated() {
            this.show();
        },
        deactivated() {
            this.hide();
        },
        beforeDestroy() {
            this.hide();
        }
    };
</script>
