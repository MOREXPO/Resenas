import { defineStore } from 'pinia'
import axios from 'axios';

export const audiovisualStore = defineStore({
  id: "audiovisual",
  state: () => ({
    loaded: false,
    loading: false,
    audiovisuals: [],
  }),
  getters: {
    getLoaded: (state) => {
      return state.loaded
    },
    getLoading: (state) => {
      return state.loading
    },
    getAudiovisual: (state) => {
      return state.audiovisuals
    }
  },
  actions: {
    async getApiAudiovisuals() {
      this.loading = true;
      axios.get('http://localhost/api/audiovisuals', {
        headers: {
          'accept': 'application/ld+json'
        }
      })
        .then(response => {
          console.log(response.data);
          this.loading = false;
        })
        .catch(error => {
          console.error('Error:', error);
          this.loading = false;
        });
    },
    setLoading(valor) {
      this.loading = valor
    },
    updateGroup(entity) {
      let newList = [...this.audiovisuals.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
