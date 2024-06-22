import { defineStore } from 'pinia';
import axios from 'axios';
import { useNotification } from "@kyvg/vue3-notification";

const { notify } = useNotification()

export const audiovisualStore = defineStore({
  id: "audiovisual",
  state: () => ({
    audiovisuals: {},
    store: [],
    lastPage: {},
    loading: true,
  }),
  getters: {
    getAudiovisuals: (state) => {
      return state.audiovisuals;
    },
    getStore: (state) => {
      return state.store;
    },
    getLastPage: (state) => {
      return state.lastPage;
    },
    getLoading: (state) => {
      return state.loading;
    },
  },
  actions: {
    async getApiAudiovisuals(page, search = '', sortBy = 'id', orderBy = 'desc') {
      if (!(search in this.audiovisuals)) {
        this.audiovisuals[search] = {};
      }
      if (!(sortBy in this.audiovisuals[search])) {
        this.audiovisuals[search][sortBy] = {};
      }
      if (!(orderBy in this.audiovisuals[search][sortBy])) {
        this.audiovisuals[search][sortBy][orderBy] = {};
      }
      if (!(page in this.audiovisuals[search][sortBy][orderBy])) {
        this.loading = true;
        try {
          let response = null;
          if (sortBy == 'valoracion') {
            if (!Array.isArray(this.audiovisuals[search][sortBy][orderBy][page])) {
              this.audiovisuals[search][sortBy][orderBy][page] = [];
            }
            axios.get(`http://localhost/api/audiovisuals/medias/${page}/${orderBy}`, {
              headers: {
                'accept': 'application/ld+json',
              },
            }).then((response) => {
              let content = response.data;
              console.log(content);
              content['results'].forEach(x => {
                axios.get(`http://localhost/api/audiovisuals/${x.audiovisual_id}`, {
                  headers: {
                    'accept': 'application/ld+json',
                  },
                }).then((response) => {
                  this.audiovisuals[search][sortBy][orderBy][page].push(response.data);
                })
              });
              if (!(search in this.lastPage)) {
                this.lastPage[search] = {};
              }
              if (!(sortBy in this.lastPage[search])) {
                this.lastPage[search][sortBy] = {};
              }
              this.lastPage[search][sortBy][orderBy] = content['totalPages'];
              //ordenar por desc o asc audiovisuals
              const idToPosition = {};
              content['results'].forEach((item, index) => {
                idToPosition[item.audiovisual_id] = index;
              });

              // Ordenar this.audiovisuals basado en el diccionario idToPosition
              this.audiovisuals[search][sortBy][orderBy][page].sort((a, b) => {
                return idToPosition[a.id] - idToPosition[b.id];
              });

              // Comprobar el resultado
              console.log(this.audiovisuals[search][sortBy][orderBy][page]);

            });
          } else {
            response = await axios.get(`http://localhost/api/audiovisuals?nombre=${search}&page=${page}&order[${sortBy}]=${orderBy}`, {
              headers: {
                'accept': 'application/ld+json',
              },
            });
            this.$patch(state => {
              state.audiovisuals[search][sortBy][orderBy][page] = response.data['hydra:member'];
            });
            if (!(search in this.lastPage)) {
              this.lastPage[search] = {};
            }
            if (!(sortBy in this.lastPage[search])) {
              this.lastPage[search][sortBy] = {};
            }
            if (response.data['hydra:view'] && response.data['hydra:view']['hydra:last']) {
              const match = response.data['hydra:view']['hydra:last'].match(/page=(\d+)/);
              if (match) {
                this.lastPage[search][sortBy][orderBy] = parseInt(match[1], 10);
              }
            } else {
              this.lastPage[search][sortBy][orderBy] = 1;
            }

            console.log(this.audiovisuals);
          }
        } catch (error) {
          console.error('Error:', error);
        } finally {
          this.loading = false;
        }
      }
    },
    async getApiAudiovisual(id) {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/api/audiovisuals/' + id);
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
});