<template>
    <div class="register-form">
        <h2>Регистрация</h2>
        <form @submit.prevent="register">
            <div>
                <label for="name">Имя:</label>
                <input type="text" v-model="name" required />
            </div>
            <div>
                <label for="email">Электронная почта:</label>
                <input type="email" v-model="email" required />
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" v-model="password" required />
            </div>
            <div>
                <label for="password_confirmation">Подтверждение пароля:</label>
                <input type="password" v-model="password_confirmation" required />
            </div>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            error: ''
        };
    },
    methods: {
        async register() {
            try {
                const response = await axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });

                localStorage.setItem('token', response.data.token);
                this.$router.push('/dashboard');
            } catch (error) {
                this.error = 'Ошибка регистрации. Попробуйте снова.';
            }
        }
    }
};
</script>

<style scoped>
.error {
    color: red;
}
</style>
