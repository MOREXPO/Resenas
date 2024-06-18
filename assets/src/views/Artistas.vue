<template>
  <v-container>
    <v-row>
      <v-col>
        <h1>Artistas</h1>
      </v-col>
    </v-row>
    <v-row v-if="personas.length" id="content">
      <v-col v-for="persona in personas" :key="persona.id" cols="12" sm="6" md="4">
        <v-card class="mx-auto my-8" elevation="16" max-width="344">
          <v-card-item>
            <v-card-title>{{ persona.nombre }}</v-card-title>
            <v-card-subtitle>{{ formatDate(persona.fechaNacimiento) }}</v-card-subtitle>
          </v-card-item>
          <v-card-text class="bg-surface-light pt-4">
            <div><strong>Nacionalidad:</strong> {{ persona.nacionalidad }} minutos</div>
          </v-card-text>
        </v-card>
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
export default {
  name: "HomeView",
  computed: {
    ...mapState(personaStore, {
      personas: store => store.personas,
    }),
  },
  methods: {
    ...mapActions(personaStore, ["getApiPersonas"]),
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    truncate(text, length = 100) {
      return text.length > length ? text.substring(0, length) + '...' : text;
    }
  },
  mounted() {
    this.getApiPersonas();
  },

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

.v-card {
  direction: ltr;
  transition: transform 0.3s;
}

.v-card:hover {
  transform: scale(1.05);
}
</style>