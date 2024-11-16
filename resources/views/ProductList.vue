<template>
    <v-row v-if="products.length" class="mt-4">
        <v-col v-for="product in products" :key="product.id" cols="12" md="4">
            <product-card
                :product="product"
                :expiringTransactions="expiringTransactions"
                @buy="handleBuy"
                @rent="handleRent"
                @extendRent="handleExtendRent"
            />
        </v-col>
    </v-row>
</template>

<script>
import ProductCard from './ProductCard.vue';

export default {
    components: {
        ProductCard,
    },
    props: {
        products: {
            type: Array,
            required: true,
        },
        expiringTransactions: {
            type: Array,
            required: true,
        },
    },
    emits: ['buy', 'rent', 'extendRent'],
    mounted() {
        console.log('Expiring Transactions:', this.expiringTransactions);
    },
    methods: {
        handleBuy(productId) {
            this.$emit('buy', productId);
        },
        handleRent(rentData) {
            this.$emit('rent', rentData);
        },
        handleExtendRent(extendData) {
            this.$emit('extendRent', extendData);
        },
    },
};
</script>

<style scoped>
.mt-4 {
    margin-top: 1rem;
}
</style>
