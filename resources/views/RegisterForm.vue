<template>
    <v-form ref="registerForm" v-model="valid" @submit.prevent="registerUser">
        <v-text-field
            v-model="name"
            label="Имя"
            outlined
            :rules="[rules.required]"
            :error-messages="errors.name"
            @input="clearError('name')"
        ></v-text-field>

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
            :rules="[rules.required, rules.minPassword]"
            :error-messages="errors.password"
            @input="clearError('password')"
        ></v-text-field>

        <v-text-field
            v-model="password_confirmation"
            label="Подтверждение пароля"
            type="password"
            outlined
            :rules="[rules.required, rules.matchPassword]"
            :error-messages="errors.password_confirmation"
            @input="clearError('password_confirmation')"
        ></v-text-field>

        <v-btn type="submit" color="primary">Зарегистрироваться</v-btn>
    </v-form>
</template>

<script>

import axios from '../js/axios';
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            valid: false,
            errors: {
                name: [],
                email: [],
                password: [],
                password_confirmation: []
            },
            rules: {
                required: value => !!value || 'Обязательное поле',
                email: value => /.+@.+\..+/.test(value) || 'Введите корректный email',
                minPassword: value => value.length >= 8 || 'Пароль должен быть не менее 8 символов',
                matchPassword: value => value === this.password || 'Пароли не совпадают',
            }
        };
    },
    methods: {
        async registerUser() {
            try {
                const response = await axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                localStorage.setItem('token', response.data.token);
                this.$emit('registered');
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
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
