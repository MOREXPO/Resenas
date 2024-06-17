import { defineStore } from 'pinia'
import axios from 'axios';

export const elencoStore = defineStore({
  id: "elenco",
  state: () => ({
    loaded: false,
    loading: false,
    elencos: [],
  }),
  getters: {
    getLoaded: (state) => {
      return state.loaded
    },
    getLoading: (state) => {
      return state.loading
    },
    getElencos: (state) => {
      return state.elencos
    }
  },
  actions: {
    async getApiElencos() {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/api/elencos');
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
      let newList = [...this.elencos.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
