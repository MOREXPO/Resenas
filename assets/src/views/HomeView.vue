<template>
    <p>{{ audiovisuals }}</p>
</template>
<script>
import { mapState, mapActions } from 'pinia';
import { audiovisualStore } from '../stores/audiovisual';
export default {
    name: "HomeView",
    computed: {
        ...mapState(audiovisualStore, {
            audiovisuals: store => store.audiovisuals,
        }),
    },
    methods: {
        ...mapActions(audiovisualStore, ["getApiAudiovisuals"]),
    },
    mounted() {
        this.getApiAudiovisuals();
    },

}
</script>
