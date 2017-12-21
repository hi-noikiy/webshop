
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";

import Notification from './components/NotificationComponent'
import Footer from './components/FooterComponent'
import Logs from './components/LogComponent'

import ContactEmail from './components/Account/ContactEmailComponent';
import AddressList from './components/Account/AddressListComponent';

import Price from './components/Catalog/PriceComponent'

import Cart from './components/Checkout/CartComponent'
import AddToCart from './components/Checkout/AddToCartComponent'
import MiniCart from './components/Checkout/MiniCartComponent'
import CartAddress from './components/Checkout/Address/CartAddressComponent'

import FavoritesButton from './components/Favorites/ButtonComponent'

import 'vue-googlemaps/dist/vue-googlemaps.css'
import VueGoogleMaps from 'vue-googlemaps'

Vue.use(VueGoogleMaps, {
    load: {
        apiKey: 'AIzaSyAQ1vHHgU-naArhlFsQOZKcy_Lp3yPHh7Y',
        libraries: ['places'],
    },
});

window.vm = new Vue({
    el: '#app',
    components: {
        'price': Price,
        'cart': Cart,
        'add-to-cart': AddToCart,
        'mini-cart': MiniCart,
        'favorites-button': FavoritesButton,
        'notification': Notification,
        'footer-block': Footer,
        'logs': Logs,
        'contact-email': ContactEmail,
        'address-list': AddressList,
        'cart-address': CartAddress
    },
    data () {
        return {
            skus: []
        }
    },
    methods: {
        fetchPrices () {
            axios.post('/fetchPrices', {
                skus: this.$data.skus
            })
                .then((response) => {
                    response.data.payload.forEach((item) => {
                        this.$root.$emit('price-fetched-' + item.sku, {
                            netPrice: item.net_price,
                            grossPrice: item.gross_price,
                            pricePer: item.price_per_string
                        });
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    created () {
        this.$root.$on('fetch-price', (sku) => {
            this.$data.skus.push(sku);
        });
    },
    mounted () {
        if (window.Laravel.isLoggedIn && this.$data.skus.length > 0) {
            this.fetchPrices();
        }
    }
});