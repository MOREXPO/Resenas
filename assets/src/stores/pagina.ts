import { defineStore } from 'pinia'
import axios from 'axios';

export const paginaStore = defineStore({
  id: "pagina",
  state: () => ({
    loading: true,
    paginas: [],
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getPaginas: (state) => {
      return state.paginas
    }
  },
  actions: {
    async getApiPaginas() {
      this.loading=true;
      try {
        const response = await axios.get('http://localhost/api/paginas');
        this.paginas = response.data['hydra:member'];
        console.log(this.paginas);
        this.loading=false;
      } catch (error) {
        this.loading=false;
        console.error(error);
      }

    },
    setLoading(valor) {
      this.loading = valor
    },
  }
})
