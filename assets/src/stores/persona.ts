import { defineStore } from 'pinia'
import axios from 'axios';

export const personaStore = defineStore({
  id: "persona",
  state: () => ({
    personas: { general: {} },
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
      if (!(page in this.personas.general)) {
        this.loading = true;
        try {
          const response = await axios.get(`http://localhost/api/personas?page=${page}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          this.$patch(state => {
            state.personas.general[page] = response.data['hydra:member'];
          });
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
    async getPersonasByNombre(search) {
      if (!(search in this.personas)) {
        this.$patch(state => {
          state.personas[search] = {};
        });
        this.loading = true;
        try {
          const response = await axios.get(`http://localhost/api/personas?nombre=${search}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          console.log(response.data);
          this.$patch(state => {
            state.personas[search] = response.data['hydra:member'];
          });
          console.log(this.personas);
        } catch (error) {
          console.error('Error:', error);
        } finally {
          this.loading = false;
        }
      }
    },
    async getApiPersona(endpoint) {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost' + endpoint);
        this.$patch(state => {
          state.store = this.updateGroup(response.data);
        });
      } catch (error) {
        console.error(error);
      } finally {
        this.loading = false;
      }

    },
    updateGroup(entity) {
      let newList = [...this.store.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => a.id - b.id);
      return newList;
    }
  }
})
