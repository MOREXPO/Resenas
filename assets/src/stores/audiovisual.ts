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
      try {
        const response = await axios.get('http://localhost/api/audiovisuals');
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
      let newList = [...this.audiovisuals.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
