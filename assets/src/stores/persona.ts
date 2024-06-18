import { defineStore } from 'pinia'
import axios from 'axios';

export const personaStore = defineStore({
  id: "persona",
  state: () => ({
    personas: [],
  }),
  getters: {
    getPersonas: (state) => {
      return state.personas
    }
  },
  actions: {
    async getApiPersonas() {
      axios.get('http://localhost/api/personas', {
        headers: {
          'accept': 'application/ld+json'
        }
      })
        .then(response => {
          this.personas = response.data['hydra:member'];
          console.log(this.personas);
        })
        .catch(error => {
          console.error('Error:', error);
        });
    },
    updateGroup(entity) {
      let newList = [...this.personas.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
