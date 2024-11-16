<template>
    <div class="login-form">
        <h2>Вход</h2>
        <form @submit.prevent="login">
            <div>
                <label for="email">Электронная почта:</label>
                <input type="email" v-model="email" required />
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" v-model="password" required />
            </div>
            <button type="submit">Войти</button>
        </form>
        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            error: ''
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                });

                localStorage.setItem('token', response.data.token);
                this.$router.push('/dashboard');
            } catch (error) {
                this.error = 'Неверные учетные данные. Попробуйте снова.';
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
