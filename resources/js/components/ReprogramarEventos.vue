<template>
    <div class="ui segment" style="height: 100%; min-height: 100vh; overflow: scroll;">
        <h2 class="ui center aligned icon header"><i class="calendar alternate outline icon">
            </i> RE-PROGRAMAR EVENTO
        </h2>
        <div class="ui three column centered grid">
            <div class="column">
                <div class="ui form">
                    <h4 class="ui dividing header">Información del Evento</h4>
                    <div class="three fields">
                        <div class="field">
                            <label>Materia</label>
                            <input type="text" placeholder="Materia" readonly="" v-model="event.materia_nombre">
                        </div>
                        <div class="field">
                            <label for="aulas">Aula</label>
                            <select id="aulas" v-model="event.aula">
                                <option v-for="(aula, index) in aulas" :value="aula">{{ aula }}</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="importancia">Nivel de Importancia <span v-if="event.importancia !== null"
                                                                                style="margin: auto 0px;"
                                                                                :class="'ui ' + nivelImportancia + ' empty circular large label'"></span></label>
                            <select id="importancia" v-model="event.importancia">
                                <option v-for="(nivel, index) in niveles" :value="nivel.id">{{ nivel.nombre }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>Fecha</label>
                            <VueCtkDateTimePicker :position="'bottom'" :label="''"
                                                  :input-size="'sm'" v-model="event.fecha" :only-date="true"
                                                  :formatted="'YYYY-MM-DD'"/>
                        </div>
                        <div class="field">
                            <label>Hora Inicio</label>
                            <VueCtkDateTimePicker :position="'bottom'" :label="''"
                                                  :input-size="'sm'" v-model="event.hora" :only-time="true"
                                                  :format="'HH:mm'" :formatted="'HH:mm'"/>
                        </div>
                        <div class="field">
                            <label>Hora Fin</label>
                            <VueCtkDateTimePicker :position="'bottom'" :label="''"
                                                  :input-size="'sm'" v-model="event.hora_fin" :only-time="true"
                                                  :format="'HH:mm'" :formatted="'HH:mm'"/>
                        </div>
                    </div>
                    <div class="ui two column grid">
                        <div class="column">
                            <label>Titulo</label>
                            <input type="text" placeholder="Titulo" v-model="event.titulo">
                        </div>
                        <div class="column">
                            <label>Descripcion</label>
                            <textarea rows="2" v-model="event.descripcion"></textarea>
                        </div>
                    </div>
                    <div class="ui green button" tabindex="0" @click="reprogramarEvento">Re-programar</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
    import moment from "moment";

    export default {
        name: "ReprogramarEventos",
        mounted(){
            //Lleno con los campos que vienen del prop
            console.log("evento", this.evento);
            console.log("materia", this.evento.materia_docente.materia);
            this.event.id = this.evento.id;
            this.event.importancia = this.evento.importancia;
            this.event.fecha = this.evento.fecha;
            this.event.hora = this.evento.hora;
            this.event.hora_fin = this.evento.hora_fin;
            this.event.descripcion = this.evento.descripcion;
            this.event.aula = this.evento.aula;
            this.event.materia = this.evento.materia_docente.materia.id;
            this.event.materia_nombre = this.evento.materia_docente.materia.nombre;
            this.event.titulo = this.evento.titulo;

            /*this.$set(this.event, "materia", materia_docente.materia.nombre);*/
            //Pregunto por el nivel de importancia que tiene
        },
        computed: {
            nivelImportancia() {
                console.log("computando... ", this.event.importancia);
                return this.niveles.filter((value) => {
                    return value.id === this.event.importancia;
                })[0].color;
            }
        },

        components:{
            VueCtkDateTimePicker
        },

        props:{
           evento: {type: Object, required: true}
        },

        data(){
            return {
                event: {
                    id: null,
                    importancia: null,
                    fecha: "",
                    hora: "",
                    cambio_horario: false,
                    descripcion: "",
                    aula: 0,
                    materia: 0,
                    titulo: "",
                    hora_fin: "",
                    materia_nombre: ""
                },
                aulas: ["101", "102", "103", "104", "105", "106"],
                materias: [],
                niveles: [
                    {
                        id: 1,
                        nombre: "Alto",
                        color: "red"
                    },
                    {
                        id: 2,
                        nombre: "Medio",
                        color: "yellow",
                    },
                    {
                        id: 3,
                        nombre: "Normal",
                        color: "green"
                    }
                ]
            }
        },
        methods:{
            async reprogramarEvento(){
                const { data } = await axios.post('nueva/actividad', this.event);
                if(data.guardado === true && data.conflicto === false){
                    this.$swal({
                        title: "La tarea ha sido re-programada!!!",
                        type: 'success',
                    }).then((result) => {
                        if(result.value){
                            location.href = "/AgendaUniversitaria/public/home";
                        }
                    });
                }else{
                    this.$swal({
                        title: "Conflicto al re-programar la tarea, revisar los tiempos!!!",
                        type: 'warning',
                    });
                }
                console.log(this.event);
            }
        }
    }
</script>

<style scoped>

</style>
