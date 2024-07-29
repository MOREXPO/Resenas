<template>
    <div>
        <Navbar v-if="isMobile"></Navbar>
        <NavigationDrawer class="ms-2" v-else></NavigationDrawer>
    </div>
</template>
<script>
import { ref, onMounted, onUnmounted } from 'vue'
import NavigationDrawer from '../components/NavigationDrawer.vue'
import Navbar from '../components/Navbar.vue'

export default {
    name: "head",
    components: {
        NavigationDrawer,
        Navbar,
    },
    data() {
        return {
            isMobile: ref(false)
        };
    },
    methods: {
        handleResize() {
            this.isMobile = window.innerWidth <= 768 // Ajusta el valor según tus necesidades
        }
    },
    mounted() {
        this.handleResize();
        window.addEventListener('resize', this.handleResize);
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.handleResize)
    }
}
</script>