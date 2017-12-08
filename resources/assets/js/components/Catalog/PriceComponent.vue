<template>
    <div class="prices" :class="{ 'price-loading': fetching, 'price-loaded': !fetching }">
        <div class="loading-animation text-center" v-if="fetching">
            Uw prijs wordt opgehaald <br />
            <i class="fa fa-spinner fa-spin"></i>
        </div>

        <div class="gross-price" v-if="grossPrice !== false">
            Bruto: &euro; <span>{{ grossPrice }}</span>
        </div>

        <div class="net-price" v-if="netPrice !== false">
            Netto: &euro; <span>{{ netPrice }}</span>
        </div>

        <small class="form-text text-muted price-per" v-if="pricePer !== false">
            {{ pricePer }}
        </small>
    </div>
</template>

<style scoped lang="scss">
    @import url('https://fonts.googleapis.com/css?family=Exo+2:700');

    .gross-price,
    .net-price {
        font-family: 'Exo 2', sans-serif;
        font-size: 20px;
        color: #125175;
    }

    .price-per {
        margin-top: 15px
    }
</style>

<script>
    export default {
        props: ['product'],
        data () {
            return {
                fetching: true,
                netPrice: false,
                grossPrice: false,
                pricePer: false
            }
        },
        created () {
            this.$root.$emit('fetch-price', this.product.sku);

            this.$root.$on('price-fetched-' + this.product.sku, (data) => {
                this.$data.fetching = false;
                this.$data.netPrice = data.netPrice;
                this.$data.grossPrice = data.grossPrice;
                this.$data.pricePer = data.pricePer;
            });
        }
    }
</script>