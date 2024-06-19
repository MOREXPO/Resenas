<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Artistas</h1>
      </v-col>
    </v-row>
    <v-row v-if="!personasLoading" id="content">
      <v-col v-for="artista in artistas" :key="artista.id" cols="12" sm="6" md="4">
        <ArtistaCard :artista="artista"></ArtistaCard>
      </v-col>
      <v-col :cols="12" class="text-center paginacion">
        <v-pagination v-model="page" class="my-4" :total-visible="7" :length="lastPage" color="primary"></v-pagination>
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col>
        <v-progress-circular indeterminate></v-progress-circular>
        <p>Cargando...</p>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapState, mapActions } from 'pinia';
import { personaStore } from '../stores/persona';
import { etiquetaStore } from '../stores/etiqueta';
import ArtistaCard from '../components/ArtistaCard.vue';
export default {
  name: "Artistas",
  data() {
    return {
      page: 1,
    }
  },
  components: {
    ArtistaCard
  },
  computed: {
    ...mapState(personaStore, {
      personas: store => store.personas,
      lastPage: store => store.lastPage,
      personasLoading: store => store.loading,
    }),
    artistas() {
      return this.personas[this.page];
    },
  },
  methods: {
    ...mapActions(personaStore, ["getApiPersonas"]),
    ...mapActions(etiquetaStore, ["getApiEtiquetas", "setLoading"]),
  },
  created() {
    this.getApiPersonas(this.page).then(() => {
      this.personas.forEach((page) => {
        page.forEach(persona => {
          persona.etiquetas.forEach(etiqueta => {
            this.getApiEtiquetas(etiqueta);
          });
        });
      });
    });
  },
  watch: {
    page(newValue, oldValue) {
      this.page = newValue;
      this.getApiPersonas(newValue);
    }
  }
}
</script>
<style scoped>
#content {
  position: relative;
  flex: 1;
  width: 100%;
  direction: rtl;
  overflow-y: auto;
  overflow-x: hidden;
  transition: width 0.2s;
  max-height: 80vh;
}

#content::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

#content::-webkit-scrollbar-thumb {
  border-radius: 99px;
  background-color: #d62929;
}

#content::-webkit-scrollbar-button {
  height: 16px;
}

.paginacion {
  direction: ltr;
}

@media screen and (max-width: 600px) {
  .card {
    width: 100%;
    height: auto;
  }
}
</style>