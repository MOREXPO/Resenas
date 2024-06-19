import { defineStore } from 'pinia'
import axios from 'axios';

export const etiquetaStore = defineStore({
  id: "etiqueta",
  state: () => ({
    loading: true,
    etiquetas: [],
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getEtiquetas: (state) => {
      return state.etiquetas
    }
  },
  actions: {
    async getApiEtiquetas(endpoint) {
      if (this.etiquetas.some(etiqueta => etiqueta['@id'] !== endpoint) || this.etiquetas.length === 0) {
        try {
          const response = await axios.get('http://localhost' + endpoint);
          this.etiquetas = this.updateGroup(response.data);
          console.log(this.etiquetas);
        } catch (error) {
          console.error(error);
        }
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
