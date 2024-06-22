<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Películas</h1>
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
        <v-row v-if="!audiovisualLoading" id="content">
            <v-row style="direction: ltr;">
                <v-col v-for="pelicula in peliculas" :key="pelicula.id" cols="3" md="3" sm="6" xs="12">
                    <PeliculaCard :pelicula="pelicula"></PeliculaCard>
                </v-col>
                <v-col :cols="12" class="text-center paginacion">
                    <v-pagination v-model="page" class="my-4" :total-visible="7"
                        :length="lastPage[search][sortBy][orderBy]" color="primary"></v-pagination>
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
import { audiovisualStore } from '../stores/audiovisual';
import PeliculaCard from '../components/PeliculaCard.vue';
import ButtonLike from '../components/ButtonLike.vue';

export default {
    name: "Peliculas",
    data() {
        return {
            page: 1,
            search: '',
            sortBy: 'id', // Criterio inicial de ordenamiento
            orderBy: 'desc',
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
                { text: 'Valoración', value: 'valoracion' },
                // Agrega más opciones de ordenamiento según tus necesidades
            ];
        },
        orderOptions() {
            return [
                { text: 'Ascendente', value: 'asc' },
                { text: 'Descendente', value: 'desc' },
            ];
        },
        peliculas() {
            return this.audiovisuals[this.search][this.sortBy][this.orderBy][this.page]
                ? this.audiovisuals[this.search][this.sortBy][this.orderBy][this.page]
                : [];
        }
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisuals"]),
    },
    created() {
        this.getApiAudiovisuals(this.page, this.search, this.sortBy, this.orderBy);
    },
    watch: {
        page(newValue) {
            this.getApiAudiovisuals(newValue, this.search, this.sortBy, this.orderBy);
        },
        search(newValue) {
            this.page = 1;
            this.getApiAudiovisuals(this.page, newValue, this.sortBy, this.orderBy);
        },
        sortBy(newValue) {
            this.page = 1;
            this.getApiAudiovisuals(this.page, this.search, newValue, this.orderBy);
        },
        orderBy(newValue) {
            this.page = 1;
            this.getApiAudiovisuals(this.page, this.search, this.sortBy, newValue);
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