import { defineStore } from 'pinia'
import axios from 'axios';

export const inteligenciaStore = defineStore({
  id: "inteligencia",
  state: () => ({
    loading: true,
    ias: [],
  }),
  getters: {
    getLoading: (state) => {
      return state.loading
    },
    getIas: (state) => {
      return state.ias
    }
  },
  actions: {
    async getApiIas(endpoint) {
      this.loading=true;
      if (this.ias.some(ia => ia['@id'] !== endpoint) || this.ias.length === 0) {
        try {
          const response = await axios.get('http://localhost' + endpoint);
          this.ias = this.updateGroup(response.data);
          this.loading=false;
        } catch (error) {
          this.loading=false;
          console.error(error);
        }
      }
    },
    setLoading(valor) {
      this.loading = valor
    },
    updateGroup(entity) {
      let newList = [...this.ias.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
