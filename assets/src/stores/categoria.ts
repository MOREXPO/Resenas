import { defineStore } from 'pinia'
import axios from 'axios';

export const categoriaStore = defineStore({
  id: "categoria",
  state: () => ({
    loading: true,
    categorias: [],
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getCategorias: (state) => {
      return state.categorias
    }
  },
  actions: {
    async getApiCategorias() {

      try {
        const response = await axios.get('http://localhost/api/categorias');
        this.categorias = response.data['hydra:member'];
        console.log(this.categorias);
      } catch (error) {
        console.error(error);
      }

    },
    setLoading(valor) {
      this.loading = valor
    },
  }
})
