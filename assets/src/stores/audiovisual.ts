import { defineStore } from 'pinia'
import axios from 'axios';

export const audiovisualStore = defineStore({
  id: "audiovisual",
  state: () => ({
    audiovisuals: [],
    store: [],
    lastPage: 0,
    loading: true,
  }),
  getters: {
    getAudiovisuals: (state) => {
      return state.audiovisuals
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
    async getApiAudiovisuals(page) {
      if (!(page in this.audiovisuals)) {
        this.loading = true;
        try {
          const response = await axios.get(`http://localhost/api/audiovisuals?page=${page}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          this.audiovisuals[page] = response.data['hydra:member'];
          const match = response.data['hydra:view']['hydra:last'].match(/page=(\d+)/);
          if (match) {
            this.lastPage = parseInt(match[1], 10);
          }
          console.log(this.audiovisuals);
          this.loading = false;
        } catch (error) {
          this.loading = false;
          console.error('Error:', error);
        }
      }
    },
    async getApiAudiovisual(id) {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/api/audiovisuals/' + id);
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
