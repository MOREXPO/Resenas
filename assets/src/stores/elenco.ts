import { defineStore } from 'pinia'
import axios from 'axios';

export const elencoStore = defineStore({
  id: "elenco",
  state: () => ({
    loading: true,
    elencos: [],
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getElencos: (state) => {
      return state.elencos
    }
  },
  actions: {
    async getApiElencos(endpoint) {
      if (this.elencos.some(elenco => elenco['@id'] !== endpoint) || this.elencos.length === 0) {
        try {
          const response = await axios.get('http://localhost' + endpoint);
          this.elencos = this.updateGroup(response.data);
        } catch (error) {
          console.error(error);
        }
      }
    },
    setLoading(valor) {
      this.loading = valor
    },
    updateGroup(entity) {
      let newList = [...this.elencos.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
