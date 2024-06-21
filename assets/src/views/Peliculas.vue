<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Películas</h1>
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
        <v-row v-if="!audiovisualLoading" id="content">
            <v-col v-for="pelicula in filteredAndSortedPeliculas" :key="pelicula.id" cols="3" md="3" sm="6" xs="12">
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
import PeliculaCard from '../components/PeliculaCard.vue';
import ButtonLike from '../components/ButtonLike.vue';

export default {
    name: "Peliculas",
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
        PeliculaCard,
        ButtonLike
    },
    computed: {
        ...mapState(audiovisualStore, {
            audiovisuals: store => store.audiovisuals,
            lastPage: store => store.lastPage,
            audiovisualLoading: store => store.loading,
        }),
        sortOptions() {
            return [
                { text: 'ID', value: 'id' },
                { text: 'Título', value: 'nombre' },
                { text: 'Fecha de lanzamiento', value: 'fechaLanzamiento' },
                { text: 'Duración', value: 'duracion' },
                // Agrega más opciones de ordenamiento según tus necesidades
            ];
        },
        filteredAndSortedPeliculas() {
            let peliculas = this.audiovisuals[this.page] || [];
            // Aplicar filtro por búsqueda
            peliculas = peliculas.filter(pelicula =>
                pelicula.nombre.toLowerCase().includes(this.filters.search.toLowerCase())
            );
            console.log(peliculas);
            // Ordenar películas
            peliculas.sort((a, b) => {
                if (a[this.sortBy] < b[this.sortBy]) return -1;
                if (a[this.sortBy] > b[this.sortBy]) return 1;
                return 0;
            });
            return peliculas;
        },
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisuals"]),
    },
    created() {
        this.getApiAudiovisuals(this.page);
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