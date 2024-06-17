import { defineStore } from 'pinia'
import axios from 'axios';

export const categoriaStore = defineStore({
  id: "categoria",
  state: () => ({
    loaded: false,
    loading: false,
    categorias: [],
  }),
  getters: {
    getLoaded: (state) => {
      return state.loaded
    },
    getLoading: (state) => {
      return state.loading
    },
    getCategorias: (state) => {
      return state.categorias
    }
  },
  actions: {
    async getApiCategorias() {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/api/categorias');
        this.loading = false;
        console.log(response);
      } catch (error) {
        console.error(error);
        this.loading = false;
      }
    },
    setLoading(valor) {
      this.loading = valor
    },
    updateGroup(entity) {
      let newList = [...this.categorias.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
