<template>
    <v-container>
        <header-bar
            :user="user"
            @logout="handleLogout"
            @openAuthDialog="authDialog = true"
        />
        <product-list
            v-if="products.length"
            :products="products"
            :expiringTransactions="expiringTransactions"
        @buy="buyProduct"
        @rent="rentProduct"
        @extendRent="extendRent"
        />
        <auth-dialog
            :authDialog="authDialog"
            @update:authDialog="authDialog = $event"
            @authenticated="onAuthenticated"
        />
        <v-snackbar v-model="snackbar.visible" :timeout="snackbar.timeout" :color="snackbar.color">
            {{ snackbar.message }}
        </v-snackbar>
    </v-container>
</template>

<script>
import ProductList from './ProductList.vue';
import AuthDialog from './AuthDialog.vue';
import HeaderBar from './Header.vue';
import axios from '../js/axios';

export default {
    components: {
        ProductList,
        AuthDialog,
        HeaderBar,
    },
    data() {
        return {
            products: [],
            expiringTransactions: [],
            authDialog: false,
            user: null,
            snackbar: {
                visible: false,
                message: '',
                timeout: 3000,
                color: 'success',
            },
        };
    },
    created() {
        this.fetchProducts();
        this.fetchUser();
    },

    methods: {
        onAuthenticated() {
            this.authDialog = false;
            this.fetchUser();
            this.fetchProducts();
        },
        handleLogout() {
            this.user = null;
            this.products = [];
            this.expiringTransactions = [];
            this.fetchProducts();
        },
        async fetchProducts() {
            try {
                const response = await axios.get('/products');
                this.products = response.data.data;
            } catch (error) {
                console.error('Ошибка при получении товаров', error);
                this.showSnackbar('Ошибка при получении товаров', 'error');
            }
        },
        async fetchUser() {
            try {
                const response = await axios.get('/user');
                this.user = response.data;
                this.expiringTransactions = response.data.expiring_transactions || [];
            } catch {
                this.user = null;
                this.expiringTransactions = [];
            }
        },
        async buyProduct(productId) {
            try {
                const response = await axios.post(`/products/${productId}/buy`);
                if (response.data.error) {
                    this.showSnackbar('Ошибка при покупке товара: ' + response.data.error, 'error');
                } else {
                    this.showSnackbar('Товар успешно куплен', 'success');
                }
            } catch (data) {
                const error = data?.response?.data?.error || '';
                console.error('Ошибка при покупке товара', error);
                this.showSnackbar(`Ошибка при покупке товара ${error}`, 'error');
            }
        },
        async rentProduct(data) {
            try {
                const response = await axios.post(`/products/${data.productId}/rent`, data);
                if (response.data.error) {
                    this.showSnackbar('Ошибка при аренде товара: ' + response.data.error, 'error');
                } else {
                    this.showSnackbar('Товар успешно арендован', 'success');
                }
            } catch (error) {
                console.error('Ошибка при аренде товара', error);
                this.showSnackbar('Ошибка при аренде товара', 'error');
            }
        },
        async extendRent(data) {
            try {
                const response = await axios.post(`/rentals/${data.transactionId}/extend`, data);
                if (response.data.error) {
                    this.showSnackbar('Ошибка при продлении аренды: ' + response.data.error, 'error');
                } else {
                    this.showSnackbar('Аренда успешно продлена', 'success');
                }
            } catch (error) {
                console.error('Ошибка при продлении аренды', error);
                this.showSnackbar('Ошибка при продлении аренды', 'error');
            }
        },
        showSnackbar(message, color = 'error') {
            this.snackbar.message = message;
            this.snackbar.color = color;
            this.snackbar.visible = true;
        },
    },
};
</script>
