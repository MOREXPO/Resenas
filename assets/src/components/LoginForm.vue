<template>

    <div v-if="!userLoading" class="container">
        <input type="radio" name="tab" id="signin" checked="checked" />
        <input type="radio" name="tab" id="register" />
        <div class="pages">
            <div class="page">
                <v-form v-model="valid" @submit.prevent="loginSubmit">
                    <div class="input">
                        <div class="title"><i class="mdi mdi-email"></i> EMAIL</div>
                        <v-text-field v-model="email" :rules="emailRules" type="email" density="compact"
                            variant="solo"></v-text-field>
                    </div>
                    <div class="input">
                        <div class="title"><i class="mdi mdi-lock"></i> CONTRASEÑA</div>
                        <v-text-field v-model="password" :rules="passwordRules"
                            :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                            :type="visible ? 'text' : 'password'" density="compact" variant="solo"
                            @click:append-inner="visible = !visible"></v-text-field>
                    </div>
                    <div class="input">
                        <v-btn type="submit">ENTRAR</v-btn>
                    </div>
                </v-form>
            </div>
            <div class="page signup">
                <v-form v-model="valid" @submit.prevent="registerSubmit">
                    <div class="input">
                        <div class="title"><i class="mdi email"></i> EMAIL</div>
                        <v-text-field v-model="emailRegister" :rules="emailRules" type="email" density="compact"
                            variant="solo"></v-text-field>
                    </div>
                    <div class="input">
                        <div class="title"><i class="mdi mdi-lock"></i> CONTRASEÑA</div>
                        <v-text-field v-model="passwordRegister" :rules="passwordRules"
                            :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                            :type="visible ? 'text' : 'password'" density="compact" variant="solo"
                            @click:append-inner="visible = !visible"></v-text-field>
                    </div>
                    <div class="input">
                        <v-btn type="submit">REGISTRARSE</v-btn>
                    </div>
                </v-form>
            </div>
        </div>
        <div class="tabs">
            <label class="tab" for="signin">
                <div class="text">Entrar</div>
            </label>
            <label class="tab" for="register">
                <div class="text">Registrar</div>
            </label>
        </div>
    </div>
    <div v-else>
        <v-container>
            <v-row class="text-center">
                <v-col>
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                    <p>Cargando...</p>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script>
import { mapState, mapActions } from 'pinia';
import { userStore } from '../stores/user';
export default {
    data() {
        return {
            email: "",
            password: "",
            emailRegister: "",
            passwordRegister: "",
            valid: false,
            visible: false,
            emailRules: [
                v => !!v || 'El correo es obligatorio',
                v => /.+@.+\..+/.test(v) || 'Correo electrónico inválido',
            ],
            passwordRules: [
                v => !!v.trim() || 'La contraseña no puede ser vacia',
            ],
        }
    },
    computed: {
        ...mapState(userStore, {
            user: store => store.user,
            userLoading: store => store.loading,
        }),
    },
    methods: {
        ...mapActions(userStore, ["login", "register"]),
        loginSubmit() {
            if (!this.valid) return
            this.login({ 'email': this.email, 'password': this.password })
        },
        registerSubmit() {
            if (!this.valid) return
            this.register({ 'email': this.emailRegister, 'password': this.passwordRegister })
        },
    }
}
</script>
<style scoped>
html,
body {
    padding: 0px;
    margin: 0px;
    background: #F8F2ED;
    font-family: "Raleway", sans-serif;
    color: #FFF;
    height: 100%;
}

.container {
    min-height: 300px;
    max-width: 250px;
    margin: auto;
    background: #FFF;
    border-radius: 2px;
    box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    -webkit-animation: hi 0.5s;
    animation: hi 0.5s;
    -webkit-transform: translateZ(0px);
}

.container * {
    box-sizing: border-box;
}

.pages {
    flex: 1;
    white-space: nowrap;
    position: relative;
    transition: all 0.4s;
    display: flex;
}

.pages .page {
    min-width: 100%;
    padding: 20px 15px;
    padding-top: 0px;
    background: linear-gradient(to left, #955DFF, #6FAAFF);
}

.pages .page:nth-of-type(1) .input {
    transform: translateX(-100%) scale(0.5);
}

.pages .page:nth-of-type(2) .input {
    transform: translateX(100%) scale(0.5);
}

.pages .page .input {
    transition: all 1s;
    opacity: 0;
    transition-delay: 0s;
}

.pages .page.signup {
    background: linear-gradient(to left, #6FAAFF, #955DFF);
}

.pages .page .title {
    color: white;
    margin-bottom: 10px;
    font-size: 14px;
    position: relative;
    line-height: 14px;
}

.pages .page .title i {
    vertical-align: text-bottom;
    font-size: 19px;
}

.pages .page .input {
    margin-top: 20px;
}

.pages .page input.text {
    background: #F6F7F9;
    border: none;
    border-radius: 4px;
    width: 100%;
    height: 40px;
    line-height: 40px;
    padding: 0px 10px;
    color: rgba(0, 0, 0, 0.5);
    outline: none;
}

.pages .page .v-btn[type=submit] {
    background: rgba(0, 0, 0, 0.5);
    color: #F6F7F9;
    height: 40px;
    line-height: 40px;
    width: 100%;
    border: none;
    border-radius: 4px;
    font-weight: 600;
}

.tabs {
    max-height: 50px;
    height: 50px;
    display: flex;
    background: #FFF;
}

.tabs .tab {
    flex: 1;
    color: #5D708A;
    text-align: center;
    line-height: 50px;
    transition: all 0.2s;
}

.tabs .tab .text {
    font-size: 14px;
    transform: scale(1);
    transition: all 0.2s;
}

input[type=radio] {
    display: none;
}

input[type=radio]:nth-of-type(1):checked~.tabs .tab:nth-of-type(1) {
    box-shadow: inset -3px 2px 5px rgba(0, 0, 0, 0.25);
    color: #3F4C7F;
}

input[type=radio]:nth-of-type(1):checked~.tabs .tab:nth-of-type(1) .text {
    transform: scale(0.9);
}

input[type=radio]:nth-of-type(1):checked~.pages {
    transform: translateX(0%);
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input {
    opacity: 1;
    transform: translateX(0%);
    transition: all 0.5s;
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input:nth-child(1) {
    transition-delay: 0.2s;
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input:nth-child(2) {
    transition-delay: 0.4s;
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input:nth-child(3) {
    transition-delay: 0.6s;
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input:nth-child(4) {
    transition-delay: 0.8s;
}

input[type=radio]:nth-of-type(1):checked~.pages .page:nth-of-type(1) .input:nth-child(5) {
    transition-delay: 1s;
}

input[type=radio]:nth-of-type(2):checked~.tabs .tab:nth-of-type(2) {
    box-shadow: inset 3px 2px 5px rgba(0, 0, 0, 0.25);
    color: #3F4C7F;
}

input[type=radio]:nth-of-type(2):checked~.tabs .tab:nth-of-type(2) .text {
    transform: scale(0.9);
}

input[type=radio]:nth-of-type(2):checked~.pages {
    transform: translateX(-100%);
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input {
    opacity: 1;
    transform: translateX(0%);
    transition: all 0.5s;
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input:nth-child(1) {
    transition-delay: 0.2s;
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input:nth-child(2) {
    transition-delay: 0.4s;
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input:nth-child(3) {
    transition-delay: 0.6s;
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input:nth-child(4) {
    transition-delay: 0.8s;
}

input[type=radio]:nth-of-type(2):checked~.pages .page:nth-of-type(2) .input:nth-child(5) {
    transition-delay: 1s;
}

@-webkit-keyframes hi {
    from {
        transform: translateY(50%) scale(0, 0);
        opacity: 0;
    }
}

@keyframes hi {
    from {
        transform: translateY(50%) scale(0, 0);
        opacity: 0;
    }
}
</style>