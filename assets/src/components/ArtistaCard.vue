<template>
    <div class="card">
        <div class="info">
            <div class="info-item">
                <v-chip class="ma-2" color="indigo" prepend-icon="mdi-account-circle">
                    {{ artista.nombre }}
                </v-chip>
            </div>
            <div class="info-item">
                <v-chip prepend-icon="mdi-cake-variant" class="ma-2" color="pink">
                    {{ formatDate(artista.fechaNacimiento) }}
                </v-chip>
            </div>
            <div class="info-item">
                <v-chip prepend-icon="mdi-flag" class="ma-2" color="orange">
                    {{ artista.nacionalidad }}
                </v-chip>
            </div>
            <div class="info-item">
                <div class="pa-2">
                    <v-responsive class="overflow-y-auto" max-height="280">
                        <v-chip-group column>
                            <v-chip v-for="etiqueta in artista.etiquetas" :key="etiqueta.id"
                                :text="etiqueta.nombre" size="x-small"></v-chip>
                        </v-chip-group>
                    </v-responsive>
                </div>
            </div>
        </div>

        <div class="cover" :style="{ 'background-image': 'url(' + artista.imagen + ')' }">
        </div>
    </div>
</template>
<script>

export default {
    name: "Artista Card",
    props: {
        artista: {
            type: Object,
            required: true
        },
    },
    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript son indexados desde 0.
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        },
    }
}
</script>
<style scoped>
.card {
    direction: ltr;
    position: relative;
    border-radius: 10px;
    width: 180px;
    height: 260px;
    background-color: whitesmoke;
    -webkit-box-shadow: 1px 1px 12px #000;
    box-shadow: 1px 1px 12px #000;
    -webkit-transform: preserve-3d;
    -ms-transform: preserve-3d;
    transform: preserve-3d;
    -webkit-perspective: 2000px;
    perspective: 2000px;
    display: flex;
    flex-direction: column;
    /* Añadido para que el texto truncado funcione correctamente */
    color: #000;
    margin: 10px;
    /* Espaciado entre las tarjetas */
}

.cover {
    top: 0;
    position: absolute;
    background-color: lightgray;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.5s;
    transform-origin: 0;
    box-shadow: 1px 1px 12px #000;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
}

.card:hover .cover {
    transition: all 0.5s;
    transform: rotateY(-80deg);
}

.overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 10px;
    color: white;
}

.info {
    padding: 10px;
    padding-left: 35px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    font-family: 'Roboto', sans-serif;
    overflow-y: auto;
    border-radius: 10px;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.info-item v-icon {
    margin-right: 8px;
    font-size: 15px;
}

.info-item span {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    /* Evita el salto de línea */
    overflow: hidden;
    /* Oculta el contenido que sobrepase */
    text-overflow: ellipsis;
    /* Añade puntos suspensivos si el contenido se recorta */
}
</style>
