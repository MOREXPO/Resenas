<template>
    <v-container>
        <v-row v-if="!audiovisualLoading" class="w-100">
            <v-col cols="12" md="10" offset-md="1">
                <div v-if="pelicula" class="review-container mx-auto">
                    <v-container>
                        <!-- Título de la película -->
                        <v-row align="center">
                            <v-col cols="12">
                                <h3 >{{ pelicula.nombre }}</h3>
                            </v-col>
                        </v-row>

                        <!-- Valoraciones -->
                        <v-row>
                            <v-col cols="12" md="4">
                                <v-icon color="primary">mdi-star-circle</v-icon>
                                <h5>Valoración Media Global: {{ valoracionMediaGlobal }}</h5>
                            </v-col>
                            <v-col cols="12" md="8">
                                <v-row>
                                    <v-col v-for="ia in ias" :key="ia.id" cols="6">
                                        <v-icon color="primary">mdi-star-circle</v-icon>
                                        <h5>Valoración Media {{ ia.nombre }}: {{ mediaValoraciones(ia)
                                            }}
                                        </h5>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-divider></v-divider>
                    <v-list class="review-list">
                        <v-list-item v-for="(resena, index) in pelicula.medio.resenas" :key="index">
                            <v-list-item-content>
                                <v-card>
                                    <v-card-title>{{ resena.autor }}</v-card-title>
                                    <v-card-text>{{ resena.texto }}</v-card-text>
                                    <v-divider></v-divider>
                                    <div class="valoracion">
                                        <v-rating
                                            :model-value="mediaValoracionesIndividual({ 'ia': null, 'resena': resena })"
                                            color="yellow darken-3" half-increments readonly></v-rating>
                                        <span>Media de las {{ ias.length }} inteligencias artificiales</span>
                                        <span class="rating-number">{{ mediaValoracionesIndividual({
                                            'ia': null,
                                            'resena': resena
                                        }) }}</span>
                                    </div>
                                    <div v-for="ia in ias" :key="ia.id" class="valoracion">
                                        <v-rating
                                            :model-value="mediaValoracionesIndividual({ 'ia': ia, 'resena': resena })"
                                            color="yellow darken-3" half-increments readonly></v-rating>
                                        <span>Hecho por {{ ia.nombre }}</span>
                                        <span class="rating-number">{{ mediaValoracionesIndividual({
                                            'ia': ia, 'resena':
                                                resena
                                        }) }}</span>
                                    </div>
                                </v-card>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </div>
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
import { inteligenciaStore } from '../stores/inteligencia';

export default {
    name: 'Resenas',
    props: {
        id: {
            type: String,
            required: true
        }
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisual"]),
        ...mapActions(inteligenciaStore, ["getApiIas"]),
        resenaValoracionMedia(calificacion) {
            return (calificacion + 1) * 2.5;
        },
        mediaValoracionesIndividual(content) {
            let totalValoraciones = 0;
            let totalCalificaciones = 0;
            if (content.ia) {
                content.ia.valoraciones.filter(x => x.resena.id == content.resena.id && x.resena.medio.audiovisuals.some(y => y.id == this.pelicula.id)).forEach(valoracion => {

                    let valoracionAux = valoracion.calificacion ? valoracion.calificacion : 0;
                    // Transformar la valoración del rango [-1, 1] al rango [0, 5]
                    let valoracionTransformada = this.resenaValoracionMedia(valoracionAux);
                    totalValoraciones += valoracionTransformada;
                    totalCalificaciones++;

                })
            } else {
                this.ias.forEach(ia => {
                    ia.valoraciones.filter(x => x.resena.id == content.resena.id && x.resena.medio.audiovisuals.some(y => y.id == this.pelicula.id)).forEach(valoracion => {

                        let valoracionAux = valoracion.calificacion ? valoracion.calificacion : 0;
                        // Transformar la valoración del rango [-1, 1] al rango [0, 5]
                        let valoracionTransformada = this.resenaValoracionMedia(valoracionAux);
                        totalValoraciones += valoracionTransformada;
                        totalCalificaciones++;

                    })
                });
            }
            let valoracionMedia = totalCalificaciones > 0 ? totalValoraciones / totalCalificaciones : 0;

            // Redondear la valoración media a un decimal
            let valoracionRedondeada = valoracionMedia.toFixed(1);

            return valoracionRedondeada;
        },
        mediaValoraciones(ia) {
            let totalValoraciones = 0;
            let totalCalificaciones = 0;
            ia.valoraciones.filter(x => x.resena.medio.audiovisuals.some(y => y.id == this.pelicula.id)).forEach(valoracion => {

                let valoracionAux = valoracion.calificacion ? valoracion.calificacion : 0;
                // Transformar la valoración del rango [-1, 1] al rango [0, 5]
                let valoracionTransformada = this.resenaValoracionMedia(valoracionAux);
                totalValoraciones += valoracionTransformada;
                totalCalificaciones++;

            })
            let valoracionMedia = totalCalificaciones > 0 ? totalValoraciones / totalCalificaciones : 0;

            // Redondear la valoración media a un decimal
            let valoracionRedondeada = valoracionMedia.toFixed(1);

            return valoracionRedondeada;
        }
    },
    computed: {
        ...mapState(audiovisualStore, {
            storeAudiovisual: store => store.store,
            audiovisualLoading: store => store.loading,
        }),
        ...mapState(inteligenciaStore, {
            ias: store => store.ias,
            iasLoading: store => store.loading,
        }),
        pelicula() {
            return this.storeAudiovisual.find(audiovisual => audiovisual.id == this.id);
        },
        valoracionMediaGlobal() {
            if (!this.pelicula) return 0;

            let totalValoraciones = 0;
            let totalCalificaciones = 0;

            this.pelicula.medio.resenas.forEach(resena => {
                resena.valoraciones.forEach(valoracion => {
                    let valoracionTransformada = (valoracion.calificacion + 1) * 2.5;
                    totalValoraciones += valoracionTransformada;
                    totalCalificaciones++;
                });
            });

            return totalCalificaciones > 0 ? (totalValoraciones / totalCalificaciones).toFixed(1) : 0;
        },

    },
    created() {
        this.getApiAudiovisual(this.id).then(() => {
            this.pelicula.medio.resenas[0].valoraciones.forEach(valoracion => {
                this.getApiIas(valoracion.inteligenciaArtificial);
            });
        });
    }
};
</script>

<style scoped>
.review-container {
    padding: 20px;
}

.title h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: var(--v-primary-lighten1);
}

.subtitle h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--v-primary-lighten2);
}

.review-list {
    max-height: 60vh;
    overflow-y: auto;
}

.review-list::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.review-list::-webkit-scrollbar-thumb {
    border-radius: 99px;
    background-color: #d62929;
}

.review-list::-webkit-scrollbar-button {
    height: 16px;
}

.resena {
    margin-bottom: 1.5rem;
    padding: 1rem;
}

.resena h3 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: var(--v-primary-lighten3);
}

.resena p {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: var(--v-primary-lighten4);
}

.valoracion {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
}

.valoracion span {
    margin-left: 0.5rem;
    font-size: 0.875rem;
    color: var(--v-primary-darken1);
}

.rating-number {
    margin-left: 0.5rem;
    font-size: 1rem;
    color: var(--v-primary-darken2);
    font-weight: bold;
}
</style>
