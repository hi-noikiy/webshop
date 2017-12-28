<template>
    <div class="input-group add-to-cart">
        <span class="input-group-addon">{{ quantity > 1 ? salesUnitPlural : salesUnitSingle }}</span>
        <input type="text" class="form-control" placeholder="Aantal" v-model="quantity">
        <span class="input-group-btn">
            <button class="btn btn-success" v-on:click="this.addToCart">
                <span><i class="fab fa-fw fa-cart-plus"></i></span>
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['sku', 'salesUnitSingle', 'salesUnitPlural', 'submitUrl'],
        data () {
            return {
                quantity: 1
            };
        },
        methods: {
            addToCart () {
                axios.put(this.submitUrl, {
                    quantity: this.$data.quantity,
                    product: this.sku
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        if (response.data.success) {
                            this.$root.$emit('update-cart-count', response.data.count);
                        }
                    })
                    .catch((error) => {
                        console.log(error);

                        this.$root.$emit('send-notify', {
                            text: "Er is een probleem opgetreden tijdens het toevoegen van het product.",
                            success: false
                        });
                    });
            }
        }
    }
</script>