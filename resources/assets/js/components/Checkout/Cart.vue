<template>
    <div id="cart-item-container">
        <div id="cart-overlay" v-if="showOverlay">
            <i class="fal fa-3x fa-sync fa-spin"></i>
        </div>

        <cart-header></cart-header>

        <div v-for="item in items.products">
            <cart-item :item="item"></cart-item>
        </div>

        <cart-footer :grand-total="items.grandTotal" :continue-url="continueUrl"></cart-footer>
    </div>
</template>

<script>
    import Header from './Cart/Header';
    import Item from './Cart/Item';
    import Footer from './Cart/Footer';

    export default {
        props: ['itemsUrl', 'continueUrl'],
        methods: {
            getItems () {
                this.$data.showOverlay = true;

                axios.get(this.itemsUrl)
                    .then((response) => {
                        const payload = response.data.payload;

                        this.$data.items.products = payload.items;
                        this.$data.items.grandTotal = payload.grandTotal;

                        this.$data.showOverlay = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        data () {
            return {
                showOverlay: true,
                items: {
                    products: [],
                    grandTotal: 0
                }
            };
        },
        created () {
            this.$root.$on('fetch-cart-items', () => {
                this.getItems();
            });

            this.$root.$on('show-cart-overlay', () => {
                this.$data.showOverlay = true;
            });

            this.$root.$on('hide-cart-overlay', () => {
                this.$data.showOverlay = false;
            });

            this.getItems();
        },
        components: {
            'cart-header': Header,
            'cart-item': Item,
            'cart-footer': Footer
        }
    }
</script>