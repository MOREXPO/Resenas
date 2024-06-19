<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Películas</h1>
            </v-col>
        </v-row>
        <v-row v-if="!audiovisualLoading" id="content">
            <v-col v-for="pelicula in peliculas" :key="pelicula.id" cols="12" sm="6" md="4">
                <PeliculaCard :pelicula="pelicula"></PeliculaCard>
            </v-col>
            <v-col :cols="12" class="text-center paginacion">
                <v-pagination v-model="page" class="my-4" :total-visible="7" :length="lastPage"
                    color="primary"></v-pagination>
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
import { audiovisualStore } from '../stores/audiovisual';
import { categoriaStore } from '../stores/categoria';
import PeliculaCard from '../components/PeliculaCard.vue';
export default {
    name: "Peliculas",
    data() {
        return {
            page: 1,
        }
    },
    components:{
        PeliculaCard
    },
    computed: {
        ...mapState(audiovisualStore, {
            audiovisuals: store => store.audiovisuals,
            lastPage: store => store.lastPage,
            audiovisualLoading: store => store.loading,
        }),
        peliculas() {
            return this.audiovisuals[this.page];
        }
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisuals"]),
        ...mapActions(categoriaStore, ["getApiCategorias"]),
    },
    created() {
        this.getApiAudiovisuals(this.page);
        this.getApiCategorias();
    },
    watch: {
        page(newValue, oldValue) {
            this.page = newValue;
            this.getApiAudiovisuals(newValue);
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