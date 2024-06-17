<script setup lang="ts">
import NavigationDrawer from './components/NavigationDrawer.vue'
import Main from './components/Main.vue'
</script>

<template>
  <div class="h-100 ml-1 mr-1" style="width: 97%;">
    <v-row no-gutters class="w-100 h-100">
      <NavigationDrawer></NavigationDrawer>
      <v-col class="w-100 h-100">
        <Main></Main>
      </v-col>
    </v-row>
  </div>
</template>