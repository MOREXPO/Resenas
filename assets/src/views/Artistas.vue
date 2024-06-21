<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Artistas</h1>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field v-model="filters.search" label="Buscar" outlined dense></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <v-select v-model="sortBy" item-title="text" item-value="value" :items="sortOptions" label="Ordenar por"
          outlined dense></v-select>
      </v-col>
    </v-row>
    <v-row v-if="!personasLoading" id="content">
      <v-col v-for="artista in artistas" :key="artista.id" cols="3">
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
import ArtistaCard from '../components/ArtistaCard.vue';
export default {
  name: "Artistas",
  data() {
    return {
      page: 1,
      filters: {
        search: '',
      },
      sortBy: 'id', // Criterio inicial de ordenamiento
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
    sortOptions() {
      return [
        { text: 'ID', value: 'id' },
        { text: 'Título', value: 'nombre' },
        { text: 'Fecha de lanzamiento', value: 'fechaLanzamiento' },
        { text: 'Duración', value: 'duracion' },
        // Agrega más opciones de ordenamiento según tus necesidades
      ];
    },
  },
  methods: {
    ...mapActions(personaStore, ["getApiPersonas"]),
  },
  created() {
    this.getApiPersonas(this.page);
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
  max-height: 70vh;
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