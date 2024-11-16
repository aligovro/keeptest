<template>
    <v-dialog v-model="localAuthDialog" max-width="500px">
        <v-card>
            <v-card-title class="headline">
                {{ isRegistering ? 'Регистрация' : 'Авторизация' }}
            </v-card-title>
            <v-card-text>
                <login-form v-if="!isRegistering" @authenticated="onAuthenticated" />
                <register-form v-if="isRegistering" @registered="onRegistered" />
            </v-card-text>
            <v-card-actions>
                <v-btn v-if="!isRegistering" @click="toggleForm" color="primary">Зарегистрироваться</v-btn>

                <v-btn v-if="isRegistering" @click="toggleForm" color="primary">Уже есть аккаунт? Войти</v-btn>

                <v-btn @click="closeDialog" color="secondary">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import LoginForm from './LoginForm.vue'
import RegisterForm from './RegisterForm.vue'

export default {
    components: {
        LoginForm,
        RegisterForm
    },
    props: {
        authDialog: Boolean,
    },
    emits: ['update:authDialog', 'authenticated'],
    data() {
        return {
            localAuthDialog: this.authDialog,
            isRegistering: false,
        };
    },
    watch: {
        authDialog(newValue) {
            this.localAuthDialog = newValue;
        },
        localAuthDialog(newValue) {
            this.$emit('update:authDialog', newValue);
        }
    },
    methods: {
        closeDialog() {
            this.localAuthDialog = false;
        },
        toggleForm() {
            this.isRegistering = !this.isRegistering;
        },
        onAuthenticated() {
            this.closeDialog();
            this.$emit('authenticated');
        },
        onRegistered() {
            this.closeDialog();
            this.$emit('authenticated');
        }
    },
};
</script>

<style scoped>
</style>
