<template>
    <v-container>
        <v-row v-if="!audiovisualLoading" class="my-5">
            <v-col>
                <v-breadcrumbs :items="['Películas', pelicula.nombre, 'Reparto']"></v-breadcrumbs>
                <v-divider class="mb-5"></v-divider>
                <h1 class="display-1">{{ pelicula.nombre }}</h1>
            </v-col>
        </v-row>
        <v-container v-if="!audiovisualLoading" id="content">
            <v-row style="direction: ltr;">
                <v-col cols="12" md="6">
                    <v-container>
                        <v-row>
                            <h2>Directores</h2>
                        </v-row>
                        <v-row>
                            <v-col v-for="director in directores" :key="director.persona.id" cols="12" sm="6" md="4">
                                <ArtistaCard :artista="director.persona"></ArtistaCard>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
                <v-col cols="12" md="6">
                    <v-container>
                        <v-row>
                            <h2>Guionistas</h2>
                        </v-row>
                        <v-row>
                            <v-col v-for="guionista in guionistas" :key="guionista.persona.id" cols="12" sm="6" md="4">
                                <ArtistaCard :artista="guionista.persona"></ArtistaCard>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
            </v-row>
            <v-row style="direction: ltr;">
                <v-col cols="12">
                    <v-container>
                        <v-row>
                            <h2>Actores y Actrices</h2>
                        </v-row>
                        <v-row>
                            <v-col v-for="actor in actores" :key="actor.persona.id" cols="12" sm="6" md="4">
                                <ArtistaCard :artista="actor.persona"></ArtistaCard>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
            </v-row>
        </v-container>
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
import ArtistaCard from '../components/ArtistaCard.vue';
export default {
    name: 'Reparto',
    components: {
        ArtistaCard
    },
    props: {
        id: {
            type: String,
            required: true
        }
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisual"]),
    },
    computed: {
        ...mapState(audiovisualStore, {
            storeAudiovisual: store => store.store,
            audiovisualLoading: store => store.loading,
        }),
        pelicula() {
            console.log(this.storeAudiovisual.find(audiovisual => audiovisual.id == this.id));
            return this.storeAudiovisual.find(audiovisual => audiovisual.id == this.id);
        },
        directores() {
            return this.pelicula.elencos.filter(x => x.etiqueta.id == 7);
        },
        guionistas() {
            return this.pelicula.elencos.filter(x => x.etiqueta.id == 6);
        },
        actores() {
            return this.pelicula.elencos.filter(x => x.etiqueta.id == 1 || x.etiqueta.id == 2);
        },
    },
    created() {
        this.getApiAudiovisual(this.id);
    },
};
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
    max-height: 65vh;
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
</style>