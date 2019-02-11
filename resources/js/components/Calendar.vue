<template>
    <div>
        <full-calendar id="calendario" @event-selected="evento_selected" :config="config" :events="events"
                       @day-click="crearEvento"></full-calendar>
        <div class="ui modal" id="modal_nuevo_evento">
            <div class="header">
                Creacion de Nuevo Evento
            </div>
            <div class="content">
                <div class="ui form">
                    <div class="three fields">
                        <div class="field">
                            <label for="importancia">Nivel de Importancia <span v-if="event.importancia !== null"
                                                                                style="margin: auto 0px;"
                                                                                :class="'ui ' + nivelImportancia + ' empty circular large label'"></span></label>
                            <select id="importancia" v-model="event.importancia">
                                <option v-for="(nivel, index) in niveles" :value="nivel.id">{{ nivel.nombre }}</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Titulo</label>
                            <input type="text" placeholder="Titulo" v-model="event.titulo"/>
                        </div>
                        <div class="field">
                            <label for="materias">Materia</label>
                            <select id="materias" v-model="event.materia">
                                <option v-for="(materia, index) in materias" :value="materia.id">{{ materia.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="three fields">
                        <div class="field">
                            <label>Fecha</label>
                            <input type="text" placeholder="Fecha" v-model="event.fecha" readonly/>
                        </div>
                        <div class="field">
                            <label>Hora</label>
                            <VueCtkDateTimePicker :minute-interval="30" :position="'bottom'" :label="''"
                                                  :input-size="'sm'" v-model="event.hora" :only-time="true"
                                                  :format="'HH:mm'" :formatted="'HH:mm'"/>
                        </div>
                        <div class="field">
                            <label for="aulas">Aulas</label>
                            <select id="aulas" v-model="event.aula">
                                <option v-for="(aula, index) in aulas" :value="aula">{{ aula }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label>Descripcion</label>
                        <textarea rows="2" v-model="event.descripcion"></textarea>
                    </div>
                </div>
            </div>
            <div class="actions">
                <div class="ui approve button" @click="guardarEvento">Guardar</div>
                <div class="ui danger button" v-if="event.id !== null" @click="eliminarEvento">Eliminar</div>
                <div class="ui cancel button">Cancelar</div>
            </div>
        </div>
    </div>
</template>

<script>
    import {FullCalendar} from 'vue-full-calendar'
    import es from "../plugins/fullcalendar/locale/es"
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

    export default {
        name: "Calendar",

        components: {
            FullCalendar,
            VueCtkDateTimePicker
        },

        //Función que se ejecuta al iniciar el componente
        async mounted() {
            //$("#modal_nuevo_evento").modal();
            console.log("component Mounted!!");
            const {data} = await axios.get("eventos");
            this.events = data;
            console.log(data);
        },

        computed: {
            nivelImportancia() {
                console.log("computando... ", this.event.importancia);
                let color = this.niveles.filter((value) => {
                    return value.id === this.event.importancia;
                })[0].color;
                return color;
            }
        },

        data() {
            return {
                event: {
                    importancia: null,
                    fecha: "",
                    cambio_horario: false
                },
                aulas: ["101", "102", "103", "104", "105", "106"],
                materias: [
                    {
                        id: 1,
                        nombre: "Algebra Lineal"
                    },
                    {
                        id: 2,
                        nombre: "Investigacion Operaciones"
                    }
                ],
                niveles: [
                    {
                        id: 1,
                        nombre: "Alto",
                        color: "red"
                    },
                    {
                        id: 2,
                        nombre: "Medio",
                        color: "yellow"
                    },
                    {
                        id: 3,
                        nombre: "Normal",
                        color: "green"
                    }
                ],
                events: [],
                config: {
                    defaultView: 'month',
                    weekends: false, // will hide Saturdays and Sundays
                    header: {
                        center: 'title',
                        right: 'today, prev, next',
                        left: 'month, basicWeek, basicDay'
                    },
                    locale: 'es',
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    eventRender: function (event, element) {
                        console.log(element);
                        console.log(event);
                        $(element).popup({
                            title: event.title,
                            content: event.msg,
                            transition: 'horizontal flip',
                            inline: false,
                            hoverable: true,
                            position: 'top center',
                            delay: {
                                show: 100,
                                hide: 150
                            }
                        });
                    },
                }
            }
        },

        methods: {
            //Configuracion para el evento click de generar evento
            crearEvento(date, jsEvent, view) {
                this.event.id = null;
                console.log("Valor seleccionado: ", date.format());
                console.log("Vista Actual: ", view.name);
                this.event.importancia = 3;
                let today = new Date();
                let h = today.getHours();
                let m = today.getMinutes();
                m = this.checkTime(m);
                //this.event.hora = h + ":" + m;
                this.event.fecha = date.format();
                this.event.aula = 101;
                this.event.materia = 1;
                this.descripcion = '';
                this.titulo = '';
                $("#modal_nuevo_evento").modal('setting', 'closable', false).modal("show");
            },

            checkTime(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            },

            async guardarEvento() {
                console.log("Titulo: ", this.event.titulo);
                console.log("Materia: ", this.event.materia);
                console.log("Fecha: ", this.event.fecha);
                console.log("Hora: ", this.event.hora);
                console.log("Descripcion", this.event.descripcion);
                console.log("Evento: ", this.event);
                const {data} = await axios.post("eventos/nuevo", this.event);
                if (data.guardado === true && data.conflicto === false) {
                    const {data} = await axios.get("eventos");
                    this.events = data;
                    this.$refs.calendario.$emit('reload-events');
                }
                console.log(data);
            },

            async eliminarEvento(){
                await axios.delete("eventos/eliminar/" + this.event.id);
                const {data} = await axios.get("eventos");
                this.events = data;
                this.$refs.calendario.$emit('reload-events');
            },

            async evento_selected(event, jsEvent, view) {
                console.log("Evento: ", event);
                if (event.url) {
                    jsEvent.preventDefault();
                    console.log("Url: ", event.url);
                    const {data} = await axios.get(event.url);
                    this.event = data;
                    this.event.materia = data.materia_docente.id_materia;
                    console.log(this.event);
                    $("#modal_nuevo_evento").modal('setting', 'closable', false).modal("show");
                }
            },
        }
    }
</script>

<style scoped>

</style>
