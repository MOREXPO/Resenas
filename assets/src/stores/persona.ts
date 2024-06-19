import { defineStore } from 'pinia'
import axios from 'axios';

export const personaStore = defineStore({
  id: "persona",
  state: () => ({
    personas: [],
    store: [],
    lastPage: 0,
    loading: true,
  }),
  getters: {
    getPersonas: (state) => {
      return state.personas
    },
    getStore: (state) => {
      return state.store
    },
    getLastPage: (state) => {
      return state.lastPage
    },
    getLoading: (state) => {
      return state.loading
    },
  },
  actions: {
    async getApiPersonas(page) {
      if (!(page in this.personas)) {
        this.loading = true;
        try {
          const response = await axios.get(`http://localhost/api/personas?page=${page}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          this.personas[page] = response.data['hydra:member'];
          const match = response.data['hydra:view']['hydra:last'].match(/page=(\d+)/);
          if (match) {
            this.lastPage = parseInt(match[1], 10);
          }
          console.log(this.personas);
          this.loading = false;
        } catch (error) {
          this.loading = false;
          console.error('Error:', error);
        }
      }
    },
    async getApiPersona(endpoint) {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost' + endpoint);
        this.store = this.updateGroup(response.data);
        this.loading = false;
      } catch (error) {
        console.error(error);
        this.loading = false;
      }

    },
    updateGroup(entity) {
      let newList = [...this.store.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
