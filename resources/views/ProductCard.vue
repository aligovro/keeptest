<template>
    <v-card>
        <v-img :src="product.image" height="200px" />
        <v-card-title>{{ product.name }}</v-card-title>
        <v-card-subtitle>{{ product.price }} ₽</v-card-subtitle>
        <v-card-actions>
            <v-btn @click="buyProduct" color="primary">Купить</v-btn>
            <v-btn @click="openRentModal" color="secondary">Арендовать</v-btn>
            <v-btn v-if="hasExpiringTransaction(product.id)" @click="openExtendRentModal" color="orange">Продлить аренду</v-btn>
        </v-card-actions>
        <RentModal
            :visible="rentDialog"
            :productId="product.id"
            @update:visible="rentDialog = $event"
            @rent="handleRent"
        />
        <ExtendRentModal
            v-if="hasExpiringTransaction(product.id)"
            :visible="extendRentDialog"
            :transactionId="getTransactionIdForProduct(product.id)"
            @update:visible="extendRentDialog = $event"
            @extendRent="handleExtendRent"
        />
    </v-card>
    <v-snackbar v-model="snackbar.visible" :timeout="snackbar.timeout" color="error">
        {{ snackbar.message }}
    </v-snackbar>
</template>

<script>
import RentModal from './RentModal.vue';
import ExtendRentModal from './ExtendRentModal.vue';

export default {
    components: {RentModal, ExtendRentModal},
    props: {
        product: {
            type: Object,
            required: true,
        },
        expiringTransactions: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            rentDialog: false,
            extendRentDialog: false,
            snackbar: {
                visible: false,
                message: '',
                timeout: 3000,
            },
        };
    },
    emits: ['buy', 'rent', 'extendRent'],
    methods: {
        buyProduct() {
            this.$emit('buy', this.product.id);
        },
        openRentModal() {
            this.rentDialog = true;
        },
        openExtendRentModal() {
            this.extendRentDialog = true;
        },
        handleRent(data) {
            this.$emit('rent', data);
        },
        handleExtendRent(data) {
            this.$emit('extendRent', data);
        },
        showSnackbar(message) {
            this.snackbar.message = message;
            this.snackbar.visible = true;
        },
        hasExpiringTransaction(productId) {
            return this.expiringTransactions.some(transaction => transaction.product_id === productId);
        },
        getTransactionIdForProduct(productId) {
            const transaction = this.expiringTransactions.find(transaction => transaction.product_id === productId);
            return transaction ? transaction.id : null;
        },
    },
};
</script>
