<template>
    <div class="mb-100">
        <v-app-bar color="primary" dark>
            <v-toolbar-title>Магазин товаров</v-toolbar-title>
            <v-spacer></v-spacer>
                <div v-if="user">
                    <v-btn text @click="logout">Выйти</v-btn>
                    <span class="ml-3">Привет, {{ user.name }}</span>
                </div>
                <div v-else>
                    <v-btn text @click="$emit('openAuthDialog')">Войти</v-btn>
                </div>
        </v-app-bar>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            default: null,
        },
    },
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$emit('logout');
        },
    },
};
</script>

<style scoped>
.ml-3 {
    margin-left: 1rem;
}
.mb-100 {
    margin-bottom: 100px;
}
</style>
