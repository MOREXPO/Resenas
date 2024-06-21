import { defineStore } from 'pinia'
import axios from 'axios';
import { useNotification } from "@kyvg/vue3-notification";

const { notify } = useNotification()

export const userStore = defineStore({
  id: "user",
  state: () => ({
    loading: true,
    user: null,
    token: "",
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getUser: (state) => {
      return state.user
    }
  },
  actions: {
    async login(creds) {
      this.loading = true;
      try {
        let response = await axios.post('http://localhost/auth', creds);
        this.token = response.data.token;
        sessionStorage.setItem("token", this.token);
        this.getApiUser();
        notify({
          type: "success",
          text: "Ha iniciado sesión con exito",
        });
      } catch (error) {
        notify({
          type: "error",
          text: "Credenciales incorrectas",
        });
        this.loading = false;
      }

    },
    async getApiUser() {
      this.loading = true;
      try {
        let response = await axios.get('http://localhost/api/user/auth/', {
          headers: {
            'Authorization': `Bearer ${this.token}`,
          }
        });
        this.user = response.data;
        console.log(this.user);
        this.loading = false;
      } catch (error) {
        notify({
          type: "warn",
          text: "No estas autenticado !!",
        });
        this.loading = false;
      }
    },
    async register(creds) {
      this.loading = true;
      try {
        const response = await axios.post('http://localhost/api/registration', creds);
        console.log(response.data);
        notify({
          type: "success",
          text: "Se ha registrado con exito",
        });
        this.loading = false;
      } catch (error) {
        notify({
          type: "error",
          text: "Error: " + error,
        });
        this.loading = false;
      }
    },

    async addPelicula(audiovisualId) {
      this.loading = true;
      try {
        const url = `http://localhost/api/user/add/${audiovisualId}`;
        const response = await axios.put(
          url, {},
          {
            headers: {
              'Authorization': `Bearer ${this.token}`
            }
          }
        );
        let content = response.data;
        if (typeof content.audiovisuals === 'object' && !Array.isArray(content.audiovisuals)) {
          content.audiovisuals = Object.values(content.audiovisuals);
        }
        console.log(content);
        this.user = content;
        this.loading = false;
      } catch (error) {
        notify({
          type: "error",
          text: "No se pudo añadir a tu lista de favoritos",
        });
        this.loading = false;
      }
    },
    async logout() {
      this.loading = true;
      sessionStorage.clear();
      this.token = "";
      this.user = null;
      await axios.get('http://localhost/logout');
      notify({
        type: "success",
        text: "Se ha cerrado sesión con exito",
      });
      this.loading = false;
    },
    setLoading(valor) {
      this.loading = valor
    },
    setToken(valor) {
      this.token = valor
    },
  }
})
