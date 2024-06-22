<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Artistas</h1>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="4">
        <v-text-field v-model="search" label="Buscar" outlined dense></v-text-field>
      </v-col>
      <v-col cols="12" md="4">
        <v-select v-model="sortBy" item-title="text" item-value="value" :items="sortOptions" label="Ordenar por"
          outlined dense></v-select>
      </v-col>
      <v-col cols="12" md="4">
        <v-select v-model="orderBy" item-title="text" item-value="value" :items="orderOptions"
          label="Criterio de ordenación" outlined dense></v-select>
      </v-col>
    </v-row>
    <v-row v-if="!personasLoading" id="content">
      <v-row style="direction: ltr;">
        <v-col v-for="artista in artistas" :key="artista.id" cols="3">
          <ArtistaCard :artista="artista"></ArtistaCard>
        </v-col>
        <v-col :cols="12" class="text-center paginacion">
          <v-pagination v-model="page" class="my-4" :total-visible="7" :length="lastPage[search][sortBy][orderBy]"
            color="primary"></v-pagination>
        </v-col>
      </v-row>
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
      search: '',
      sortBy: 'id', // Criterio inicial de ordenamiento
      orderBy: 'desc',
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
      return this.personas[this.search][this.sortBy][this.orderBy][this.page]
        ? this.personas[this.search][this.sortBy][this.orderBy][this.page]
        : [];
    },
    sortOptions() {
      return [
        { text: 'ID', value: 'id' },
        { text: 'Nombre', value: 'nombre' },
        { text: 'Fecha de nacimiento', value: 'fechaNacimiento' },
      ];
    },
    orderOptions() {
      return [
        { text: 'Ascendente', value: 'asc' },
        { text: 'Descendente', value: 'desc' },
      ];
    },
  },
  methods: {
    ...mapActions(personaStore, ["getApiPersonas", "getPersonasByNombre"]),
  },
  created() {
    this.getApiPersonas(this.page, this.search, this.sortBy, this.orderBy);
  },
  watch: {
    page(newValue, oldValue) {
      this.getApiPersonas(newValue, this.search, this.sortBy, this.orderBy);
    },
    search(newValue) {
      this.page = 1;
      this.getApiPersonas(this.page, newValue, this.sortBy, this.orderBy);
    },
    sortBy(newValue) {
      this.page = 1;
      this.getApiPersonas(this.page, this.search, newValue, this.orderBy);
    },
    orderBy(newValue) {
      this.page = 1;
      this.getApiPersonas(this.page, this.search, this.sortBy, newValue);
    },
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