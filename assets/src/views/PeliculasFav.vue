<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Películas favoritas</h1>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6">
                <v-text-field v-model="search" label="Buscar" outlined dense></v-text-field>
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
import { userStore } from '../stores/user';
import PeliculaCard from '../components/PeliculaCard.vue';

export default {
    name: "PeliculasFav",
    data() {
        return {
            search: '',
            sortBy: 'id', // Criterio inicial de ordenamiento
        }
    },
    components: {
        PeliculaCard,
    },
    computed: {
        ...mapState(audiovisualStore, {
            storeAudiovisuals: store => store.store,
            audiovisualLoading: store => store.loading,
        }),
        ...mapState(userStore, {
            user: store => store.user,
            userLoading: store => store.loading,
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
        peliculas() {
            return this.storeAudiovisuals.filter(x => this.user.audiovisuals.some(y => y == x['@id']));
        },
        filteredAndSortedPeliculas() {
            let filtered = this.peliculas.filter(pelicula => {
                return pelicula.nombre.toLowerCase().includes(this.search.toLowerCase());
            });
            let sorted = filtered.sort((a, b) => {
                if (this.sortBy === 'id') {
                    return a.id - b.id;
                } else if (this.sortBy === 'nombre') {
                    return a.nombre.localeCompare(b.nombre);
                } else if (this.sortBy === 'fechaLanzamiento') {
                    return new Date(a.fechaLanzamiento) - new Date(b.fechaLanzamiento);
                } else if (this.sortBy === 'duracion') {
                    return a.duracion - b.duracion;
                }
            });
            return sorted;
        }
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisual"]),
    },
    mounted() {
        if (this.user) {
            this.user.audiovisuals.forEach(audiovisual => {
                this.getApiAudiovisual(parseInt(audiovisual.split('/').pop(), 10));
            });
        }
    },
    watch: {
        user(newValue, oldValue) {
            this.user.audiovisuals.forEach(audiovisual => {
                this.getApiAudiovisual(parseInt(audiovisual.split('/').pop(), 10));
            });
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
