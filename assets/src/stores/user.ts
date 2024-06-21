import { defineStore } from 'pinia'
import axios from 'axios';

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
        response = await axios.get('http://localhost/api/user/auth/', {
          headers: {
            'Authorization': `Bearer ${this.token}`,
          }
        });
        this.user = response.data;
        console.log(this.user);
        this.loading = false;
      } catch (error) {
        console.error(error);
        this.loading = false;
      }

    },
    async register(creds) {
      this.loading = true;
      try {
        const response = await axios.post('http://localhost/api/registration', creds);
        console.log(response.data);
        this.loading = false;
      } catch (error) {
        console.error(error);
        this.loading = false;
      }
    },
    async logout() {
      sessionStorage.clear();
      this.token = "";
      this.user = null;
      await axios.get('http://localhost/logout');
    },
    setLoading(valor) {
      this.loading = valor
    },
  }
})
