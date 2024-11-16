<template>
    <v-form ref="loginForm" v-model="valid" @submit.prevent="authorizeUser">
        <v-select
            v-model="selectedUser"
            :items="users"
            item-title="name"
            item-value="id"
            label="Выберите пользователя"
            return-object
            @update:model-value="fillForm"
        >
        </v-select>

        <v-text-field
            v-model="email"
            label="Email"
            type="email"
            outlined
            :rules="[rules.required, rules.email]"
            :error-messages="errors.email"
            @input="clearError('email')"
        ></v-text-field>

        <v-text-field
            v-model="password"
            label="Пароль"
            type="password"
            outlined
            :rules="[rules.required]"
            :error-messages="errors.password"
            @input="clearError('password')"
        ></v-text-field>

        <v-btn type="submit" color="primary">Войти</v-btn>
    </v-form>
</template>

<script>
import axios from '../js/axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            valid: false,
            selectedUser: null,
            users: [],
            errors: {
                email: [],
                password: []
            },
            rules: {
                required: value => !!value || 'Обязательное поле',
                email: value => /.+@.+\..+/.test(value) || 'Введите корректный email',
            }
        };
    },
    created() {
        this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await axios.get('/users');
                this.users = response.data;
            } catch (error) {
                console.error('Ошибка при получении пользователей:', error);
            }
        },
        fillForm(selectedUser) {
            if (selectedUser) {
                this.email = selectedUser.email;
                this.password = 'password';
            }
        },
        async authorizeUser() {
            try {
                const response = await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                });
                const token = response.data.token;
                if (!token) {
                    throw new Error('Токен отсутствует в ответе API');
                }
                localStorage.setItem('token', token);
                this.$emit('authenticated');
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Ошибка авторизации:', error);
                }
            }
        },
        clearError(field) {
            this.errors[field] = [];
        }
    }
};
</script>

<style scoped>
</style>
