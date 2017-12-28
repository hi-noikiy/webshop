
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

import Notification from './components/Notification'
import Footer from './components/Footer'
import Logs from './components/Log'

import ContactEmail from './components/Account/ContactEmail';
import AddressList from './components/Account/AddressList';

import Price from './components/Catalog/Price'
import FavoritesButton from './components/Catalog/Favorite'

import Cart from './components/Checkout/Cart'
import AddToCart from './components/Checkout/AddToCart'
import MiniCart from './components/Checkout/MiniCart'
import CartAddress from './components/Checkout/Address/CartAddress'

import QuickSearch from './components/Search/QuickSearch';

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
        'cart-address': CartAddress,
        'quick-search': QuickSearch
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