import { defineStore } from 'pinia'
import axios from 'axios';

export const etiquetaStore = defineStore({
  id: "etiqueta",
  state: () => ({
    loaded: false,
    loading: false,
    etiquetas: [],
  }),
  getters: {
    getLoaded: (state) => {
      return state.loaded
    },
    getLoading: (state) => {
      return state.loading
    },
    getEtiquetas: (state) => {
      return state.etiquetas
    }
  },
  actions: {
    async getApiEtiquetas() {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/api/etiquetas');
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
      let newList = [...this.etiquetas.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
