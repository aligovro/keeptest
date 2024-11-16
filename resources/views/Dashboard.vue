<template>
    <div v-if="products.length">
        <h2>Товары</h2>
        <div v-for="product in products" :key="product.id">
            <p>{{ product.name }} - {{ product.price }} ₽</p>
            <button @click="buyProduct(product.id)">Купить</button>
        </div>
    </div>
    <div v-else>
        <p>Загрузка товаров...</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: [],
        };
    },
    created() {
        this.fetchProducts();
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get('/products', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                });
                this.products = response.data;
            } catch (error) {
                console.error('Ошибка при получении товаров', error);
            }
        },
        async buyProduct(productId) {
            try {
                await axios.post(`/products/${productId}/buy`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                });
                alert('Товар успешно куплен');
            } catch (error) {
                console.error('Ошибка при покупке товара', error);
            }
        }
    }
};
</script>
