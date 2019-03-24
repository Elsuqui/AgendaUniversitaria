<template>
    <div class="ui container-fluid" style="height: 100%; min-height: 100vh; overflow: scroll;">
        <div class="ui equal width grid">
            <div class="column">
                <div class="ui segments">
                    <div class="ui segment top attached">
                        <div class="ui header">Seleccion de Docentes</div>
                    </div>
                    <div class="ui segment attached">
                        <div class="ui middle aligned selection list">
                            <div class="item" v-for="docente in listado_docentes" @click="seleccionarDocente(docente)">
                                <img class="ui avatar image" :src="docente.avatar"/>
                                <div class="content">
                                    <div class="header">{{ docente.name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="ui segments">
                    <div class="ui segment top attached">
                        <div class="ui header">Seleccion de Materias</div>
                    </div>
                    <div class="ui segment attached">
                        <div class="ui large grey label">Asignando a docente:</div> <strong>{{ docente_seleccionado.name }}</strong>
                        <div class="ui grid">
                            <div class="row">
                                <div class="twelve wide column">
                                    <vue-multiselect :options="listado_materias"
                                                     v-model="materia_selected"
                                                     :allow-empty="false"
                                                     track-by="id"
                                                     label="nombre"
                                                     :show-labels="false"/>
                                </div>
                                <div class="two wide column">
                                    <button type="button" class="ui green button">
                                        <i class="plus icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="ui segments">
                    <div class="ui segment top attached">
                        <div class="ui header">Materias Asignadas</div>
                    </div>
                    <div class="ui segment attached">
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueMultiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.min.css';

    export default {
        name: "AsignacionMaterias",
        components: {
            VueMultiselect
        },
        data(){
            return {
                listado_docentes: [],
                listado_materias: [],
                materias_asignadas: [],
                materia_selected: {},
                docente_seleccionado: {}
            }
        },
        async mounted(){
            const respuesta = await axios.get("docentes");
            console.log(respuesta.data);
            this.listado_docentes = respuesta.data;

            const materias = await axios.get("/AgendaUniversitaria/public/materias");
            this.listado_materias = materias.data;
            console.log(materias.data);

        },
        methods:{
            seleccionarDocente(docente){
                console.log("docente: ", docente);
                this.docente_seleccionado = docente;
            }
        }
    }
</script>

<style scoped>

</style>
