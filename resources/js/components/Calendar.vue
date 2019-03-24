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
                    <div class="four fields">
                        <div class="field">
                            <label>Fecha</label>
                            <input type="text" placeholder="Fecha" v-model="event.fecha" readonly/>
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
                        <div class="field">
                            <label for="aulas">Aula</label>
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
                <div class="ui approve button" @click.stop="guardarEvento">Guardar</div>
                <div class="ui danger button" v-if="event.id !== null" @click.stop="eliminarEvento">Eliminar</div>
                <div class="ui cancel button" @click.stop="cerrarModal">Cancelar</div>
            </div>
        </div>
    </div>
</template>

<script>
    import {FullCalendar} from 'vue-full-calendar';
    import moment from "moment";
    import es from "../plugins/fullcalendar/locale/es";
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
                    hora: "",
                    cambio_horario: false,
                    descripcion: "",
                    aula: 0,
                    materia: 0,
                    titulo: "",
                    hora_fin: "",
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
            async crearEvento(date, jsEvent, view) {
                this.event.id = null;
                console.log("Valor seleccionado: ", date.format());
                console.log("Vista Actual: ", view.name);
                this.event.importancia = 3;
                //let today = new Date();
                let h = moment().hour();
                let m = moment().minutes();
                //m = this.checkTime(m);
                //h = this.checkTime(h);
                console.log("Hora: ", h + ":" + m);
                //this.event.hora = h + ":" + m;
                this.$set(this.event, "hora", moment().format("HH:mm"));
                this.event.fecha = date.format();
                this.event.aula = 101;
                this.event.materia = 1;
                this.event.descripcion = '';
                this.event.titulo = '';
                this.event.hora = h + ":" + m;
                this.event.hora_fin = h + ":" + m;

                //Obtener materias asignadas al docente
                const materias = await axios.get("materias/asignadas");
                console.log("materias", materias);
                this.materias = materias.data;
                $("#modal_nuevo_evento").modal("show");
                //$("#modal_nuevo_evento").modal('setting', 'closable', false).modal("show");
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

                let fecha_registro = moment(this.event.fecha + " " + this.event.hora);
                let fecha_final_duracion = moment(this.event.fecha + " " + this.event.hora_fin);
                let fecha_hoy = moment();


                console.log("duracion: ", fecha_final_duracion.diff(fecha_registro, "minutes"));

                /*console.log("fecha_programada: ", fecha_registro);
                console.log("fecha_actual: ", fecha_hoy.add(1, 'days'));
                console.log("Es la fecha mayor al hoy: ", fecha_registro.diff(fecha_hoy, "days"));*/


                //Verifico que los campos esten llenos
                if (this.event.titulo !== "") {
                    if (fecha_registro.diff(fecha_hoy, "days") >= 0) {
                        if (fecha_registro.diff(fecha_hoy, "minutes") > 0) {
                            if (fecha_final_duracion.diff(fecha_registro, "minutes")) {
                                const {data} = await axios.post("eventos/nuevo", this.event);

                                if (data.guardado === true && data.conflicto === false) {
                                    const {data} = await axios.get("eventos");
                                    this.events = data;
                                    $("#modal_nuevo_evento").modal("hide");
                                    //this.$refs.calendario.$emit('reload-events');
                                    location.reload();
                                } else {
                                    this.$swal({
                                        title: "Conflicto al guardar!!!",
                                        type: 'warning',
                                    });
                                }

                                console.log("exitoso: ", data);
                            }
                        }
                    } else {
                        this.$swal({
                            title: "No se puede planificar para una fecha anterior",
                            type: 'warning',
                        });
                    }
                } else {
                    this.$swal({
                        title: 'Se debe indicar el titulo de la Actividad!',
                        type: 'warning',
                    });
                }


            },

            async eliminarEvento() {
                await axios.delete("eventos/eliminar/" + this.event.id);
                const {data} = await axios.get("eventos");
                this.events = data;
                $("#modal_nuevo_evento").modal("hide");
                location.reload();
                //this.$refs.calendario.$emit('reload-events');
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
                    $("#modal_nuevo_evento").modal("show");
                    //$("#modal_nuevo_evento").modal('setting', 'closable', false).modal("show");
                }
            },

            cerrarModal() {
                $("#modal_nuevo_evento").modal("hide");
            }
        }
    }
</script>

<style>
    .ui.yellow.label, .ui.yellow.labels .label {
        background-color: #ffff00 !important;
    }
</style>
