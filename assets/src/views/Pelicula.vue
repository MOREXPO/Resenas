<template>
    <v-container>
        <v-row v-if="!audiovisualLoading" class="my-5">
            <v-col>
                <v-breadcrumbs :items="['Películas', pelicula.nombre]"></v-breadcrumbs>
                <v-divider class="mb-5"></v-divider>
                <h1 class="display-1">{{ pelicula.nombre }}</h1>
            </v-col>
        </v-row>
        <v-row v-if="!audiovisualLoading" id="content">
            <v-col cols="12" md="4" style="direction: ltr;">
                <v-img :src="pelicula.imagen" class="rounded-lg"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="text-center">
                                <v-rating v-model="valoracion" :item-labels="labels" readonly>
                                    <template v-slot:item-label="props">
                                        <span :class="`text-${colors[props.index]}`"
                                            class="font-weight-black text-caption">
                                            {{ props.label }}
                                        </span>
                                    </template>
                                </v-rating>
                                <pre>{{ valoracion }}</pre>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col class="text-center" cols="12">
                            <v-btn :to="{ name: 'resenas', params: { id: pelicula.id } }" color="primary">
                                <v-icon class="mr-2" color="white">mdi-message-draw</v-icon>Ver {{pelicula.medio.resenas.length}} Reseñas
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
            <v-col cols="12" md="8" style="direction: ltr;">
                <v-card class="pa-5 elevation-2">
                    <v-card-title class="headline">Detalles</v-card-title>
                    <v-card-text>
                        <v-list dense>
                            <v-list-item class="info-item">
                                <v-list-item-icon>
                                    <v-icon color="primary">mdi-clock-outline</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>Duración: {{ pelicula.duracion }} minutos</v-list-item-content>
                            </v-list-item>
                            <v-list-item class="info-item">
                                <v-list-item-icon>
                                    <v-icon color="primary">mdi-calendar</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    Fecha de Lanzamiento: {{ formatDate(pelicula.fechaLanzamiento) }}
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item class="info-item">
                                <div class="pa-2">
                                    <v-responsive class="overflow-y-auto" max-height="280">
                                        <v-chip-group column>
                                            <v-chip v-for="categoria in pelicula.medio.categorias" :key="categoria.id"
                                                :text="categoria.nombre"></v-chip>
                                        </v-chip-group>
                                    </v-responsive>
                                </div>
                            </v-list-item>
                            <v-list-item class="info-item">
                                <v-list-item-icon>
                                    <v-icon color="primary">mdi-movie-outline</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>Sinopsis: {{ pelicula.sinopsis }}</v-list-item-content>
                            </v-list-item>
                            <v-list-item class="info-item">
                                <v-list-item-content>
                                    <v-btn :to="{ name: 'reparto', params: { id: pelicula.id } }" color="primary">
                                        <v-icon class="mr-2" color="white">mdi-account-group</v-icon>Ver Reparto
                                    </v-btn>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row v-else class="text-center">
            <v-col>
                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                <p>Cargando...</p>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { audiovisualStore } from '../stores/audiovisual';

export default {
    name: "Pelicula",
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data: () => ({
        rating: 4,
        colors: ['red', 'orange', 'grey', 'cyan', 'green'],
        labels: ['bad', 'so so', 'ok', 'good', 'great'],
    }),
    computed: {
        ...mapState(audiovisualStore, {
            storeAudiovisual: store => store.store,
            audiovisualLoading: store => store.loading,
        }),
        pelicula() {
            return this.storeAudiovisual.find(audiovisual => audiovisual.id == this.id);
        },
        valoracion() {
            let totalValoraciones = 0;
            let totalCalificaciones = 0;

            this.pelicula.medio.resenas.forEach(resena => {
                resena.valoraciones.forEach(valoracion => {
                    let valoracionAux = valoracion.calificacion ? valoracion.calificacion : 0;
                    // Transformar la valoración del rango [-1, 1] al rango [0, 5]
                    let valoracionTransformada = (valoracionAux + 1) * 2.5;
                    totalValoraciones += valoracionTransformada;
                    totalCalificaciones++;
                });
            });
            console.log(totalValoraciones);
            // Calcular la media de las valoraciones transformadas
            let valoracionMedia = totalCalificaciones > 0 ? totalValoraciones / totalCalificaciones : 0;

            // Redondear la valoración media a un decimal
            let valoracionRedondeada = valoracionMedia.toFixed(1);

            return valoracionRedondeada;
        }

    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisual"]),
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        formatElenco(elencos) {
            return elencos.map(elenco => elenco.split('/').pop()).join(', ');
        },
    },
    created() {
        this.getApiAudiovisual(this.id);
    }
}
</script>

<style scoped>
.info-item {
    margin-bottom: 10px;
}

#content {
    position: relative;
    flex: 1;
    width: 100%;
    direction: rtl;
    overflow-y: auto;
    overflow-x: hidden;
    transition: width 0.2s;
    max-height: 60vh;
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

.rating-values {
    width: 25px;
}
</style>
