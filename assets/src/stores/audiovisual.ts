import { defineStore } from 'pinia';
import axios from 'axios';
import { useNotification } from "@kyvg/vue3-notification";

const { notify } = useNotification()

export const audiovisualStore = defineStore({
  id: "audiovisual",
  state: () => ({
    audiovisuals: {},
    store: [],
    lastPage: 0,
    lastPageOrder: 0,
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
    async getApiAudiovisuals(page, sortBy = null, orderBy = 'desc') {
      if (!(sortBy in this.audiovisuals)) {
        this.audiovisuals[sortBy] = {};
      }
      if (!(page in this.audiovisuals[sortBy])) {
        if (!(orderBy in this.audiovisuals[sortBy])) {
          this.audiovisuals[sortBy][orderBy] = {};
        }
        if (!(page in this.audiovisuals[sortBy][orderBy])) {
          this.loading = true;
          try {
            let response = null;
            if (sortBy && sortBy !== 'valoracion') {
              response = await axios.get(`http://localhost/api/audiovisuals?page=${page}&order[${sortBy}]=${orderBy}`, {
                headers: {
                  'accept': 'application/ld+json',
                },
              });
            } else {
              response = await axios.get(`http://localhost/api/audiovisuals?page=${page}`, {
                headers: {
                  'accept': 'application/ld+json',
                },
              });
            }

            this.$patch(state => {
              state.audiovisuals[sortBy][orderBy][page] = response.data['hydra:member'];
            });
            const match = response.data['hydra:view']['hydra:last'].match(/page=(\d+)/);
            if (match) {
              this.lastPage = parseInt(match[1], 10);
            }
            console.log(this.audiovisuals);
          } catch (error) {
            notify({
              type: "error",
              text: "Error al cargar las peliculas",
            });
            console.error('Error:', error);
          } finally {
            this.loading = false;
          }
        }
      }
    },
    async getApiAudiovisualsValoracion(page, order) {
      if (!(order in this.audiovisuals)) {
        this.$patch(state => {
          state.audiovisuals[order] = {};
        });
        this.loading = true;
        try {
          const response = await axios.get(`http://localhost/api/audiovisuals/medias/${page}/${order}`, {
            headers: {
              'accept': 'application/ld+json',
            },
          });
          let content = response.data['hydra:member'];
          content['results'].forEach(async element => {
            const response = await axios.get(`http://localhost/api/audiovisuals/${element['audiovisual_id']}`, {
              headers: {
                'accept': 'application/ld+json',
              },
            });
            this.$patch(state => {
              state.audiovisuals[order][page].push(response.data);
            });
          });

          this.lastPageOrder = content['totalPages']

          console.log(this.audiovisuals);
        } catch (error) {
          notify({
            type: "error",
            text: "Error al ordenar por valoración",
          });
          console.error('Error:', error);
        } finally {
          this.loading = false;
        }
      }
    },
    async getPeliculasByNombre(search, sortBy = null, orderBy = 'desc') {
      if (!(search in this.audiovisuals)) {
        this.$patch(state => {
          state.audiovisuals[search] = {};
        });
        if (!(sortBy in this.audiovisuals[search])) {
          this.$patch(state => {
            state.audiovisuals[search][sortBy] = {};
          });
          if (!(orderBy in this.audiovisuals[search][sortBy])) {
            this.$patch(state => {
              state.audiovisuals[search][sortBy][orderBy] = {};
            });
            this.loading = true;
            try {
              const response = await axios.get(`http://localhost/api/audiovisuals?nombre=${search}&order[${sortBy}]=${orderBy}`, {
                headers: {
                  'accept': 'application/ld+json',
                },
              });
              console.log(response.data);
              this.$patch(state => {
                state.audiovisuals[search][sortBy][orderBy] = response.data['hydra:member'];
              });
              console.log(this.audiovisuals);
            } catch (error) {
              notify({
                type: "error",
                text: "Error al buscar por nombre",
              });
              console.error('Error:', error);
            } finally {
              this.loading = false;
            }
          }
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