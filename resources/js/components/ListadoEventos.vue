<template>
    <div class="ui divided items" style="overflow: auto; height: 400px;">
        <div class="item" v-for="(event, index) in events">
            <div class="ui tiny image">
                <img src="imagenes/event-default.png">
            </div>
            <div class="content">
                <h4 class="header">{{ event.titulo }}</h4>
                <div class="meta">
                    <span class="cinema">{{ event.materia }}</span>
                </div>
                <div class="description">
                    <p>{{ event.descripcion }}</p>
                </div>
                <div class="extra">
                    <div class="ui label">Fecha: {{ event.fecha }}</div>
                    <div class="ui label">Hora: {{ event.hora }}</div>
                    <div class="ui label">Aula: {{ event.aula }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from "vuex";

    export default {
        name: "ListadoEventos",
        async mounted(){
          const { data } = await axios.get("eventos/listado");
          this.events = data;
          console.log("eventos", this.events);
        },
        data(){
            return {
                events: [
                    /*{
                        nombre: "Lección",
                        materia: "Algebra Lineal",
                        descripcion: "Lección acerca de matrices y transformaciones",
                        fecha: "2019-09-02",
                        hora: "12:30",
                        aula: "101"
                    },
                    {
                        nombre: "Deber",
                        materia: "Investigacion de Operaciones",
                        descripcion: "Ejercicios resolución de ecuaciones método simplex",
                        fecha: "2019-09-02",
                        hora: "18:30",
                        aula: "120"
                    },
                    {
                        nombre: "Deber",
                        materia: "Investigacion de Operaciones",
                        descripcion: "Ejercicios resolución de ecuaciones método simplex",
                        fecha: "2019-09-02",
                        hora: "18:30",
                        aula: "120"
                    },
                    {
                        nombre: "Deber",
                        materia: "Investigacion de Operaciones",
                        descripcion: "Ejercicios resolución de ecuaciones método simplex",
                        fecha: "2019-09-02",
                        hora: "18:30",
                        aula: "120"
                    },
                    {
                        nombre: "Deber",
                        materia: "Investigacion de Operaciones",
                        descripcion: "Ejercicios resolución de ecuaciones método simplex",
                        fecha: "2019-09-02",
                        hora: "18:30",
                        aula: "120"
                    }*/
                ]
            }
        },

        computed: {
            /*...mapState("evento"),*/

        },

        methods: {
            /*...mapActions("evento", [""])  */
        }
    }
</script>

<style scoped>

</style>
