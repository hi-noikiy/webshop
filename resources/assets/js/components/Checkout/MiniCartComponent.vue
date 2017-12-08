<template>
    <div id="mini-cart">
        <a class="nav-link" :href="cartUrl">
            Winkelwagen <span class="badge badge-secondary">{{ itemCount }}</span>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['count', 'cartUrl'],
        data () {
            return {
                itemCount: this.count
            };
        },
        mounted () {
            this.$root.$on('update-cart-count', (count) => { // here you need to use the arrow function
                this.$data.itemCount = count;
            });
        }
    }
</script>