<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import NavigationDrawer from './components/NavigationDrawer.vue'
import Main from './views/Main.vue'

const isMobile = ref(false)

const handleResize = () => {
  isMobile.value = window.innerWidth <= 768 // Ajusta el valor según tus necesidades
}

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<template>
  <div class="h-100 ml-1 mr-1" style="width: 97%;">
    <v-row no-gutters class="w-100 h-100">
      <v-layout v-if="isMobile">
        <v-app-bar color="teal-darken-4" image="https://picsum.photos/1920/1080?random">
          <template v-slot:image>
            <v-img gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"></v-img>
          </template>

          <template v-slot:prepend>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
          </template>

          <v-app-bar-title>Title</v-app-bar-title>

          <v-spacer></v-spacer>

          <v-btn icon>
            <v-icon>mdi-magnify</v-icon>
          </v-btn>

          <v-btn icon>
            <v-icon>mdi-heart</v-icon>
          </v-btn>

          <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </v-app-bar>
      </v-layout>
      <NavigationDrawer v-else></NavigationDrawer>
      <v-col class="w-100 h-100">
        <Main></Main>
      </v-col>
    </v-row>
  </div>
</template>