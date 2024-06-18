import { defineStore } from 'pinia'
import axios from 'axios';

export const audiovisualStore = defineStore({
  id: "audiovisual",
  state: () => ({
    audiovisuals: [],
  }),
  getters: {
    getAudiovisuals: (state) => {
      return state.audiovisuals
    }
  },
  actions: {
    async getApiAudiovisuals() {
      axios.get('http://localhost/api/audiovisuals', {
        headers: {
          'accept': 'application/ld+json'
        }
      })
        .then(response => {
          this.audiovisuals = response.data['hydra:member'];
          console.log(this.audiovisuals);
        })
        .catch(error => {
          console.error('Error:', error);
        });
    },
    updateGroup(entity) {
      let newList = [...this.audiovisuals.filter(x => x.id != entity.id)];
      newList.push(entity);
      newList.sort((a, b) => { return a.id - b.id; })
      return newList;
    }
  }
})
