<template>
    <div>
        <portal target-el="#menu_notificacion">
            <a class="ui floating labeled dropdown item"
               :style="'background-color: ' + (this.nueva_notificacion ? '#66ffa6' : '') + '!important;'"
               style="width: 100%;" @click="leerNotificaciones">
                <i id="campana_notificacion" class="large bell icon"></i> Alertas
                <div id="label_notificaciones" class="floating ui teal small label"
                     :style="'background-color: ' + (this.nueva_notificacion ? '#d50000' : '#00b5ad') + ' !important'">
                    {{ numNotificaciones }}
                </div>
                <div class="menu" style="overflow-x: auto; max-height: 32em;">
                    <div class="ui icon message" v-if="alertas.length === 0">
                        <i class="bell slash outline icon"></i>
                        <div class="content">
                            <div class="header">Sin alertas entrantes</div>
                        </div>
                    </div>
                    <div :class="'item notification ' + key " v-for="(alerta, key) in actual_alertas"
                         @click.stop="marcarNotificacion(alerta)">
                        <div class="ui card">
                            <div class="content">
                                <div class="center floated meta" style="position: absolute; left: 70%; top: 30%;">
                                    <div class="ui tag label"
                                         :style="'background-color: ' + obtenerColorPrioridad(alerta.data.evento.importancia) + ' !important; ' +
                                         'color: ' + obtenerColorTexto(alerta.data.evento.importancia) + ';'">
                                        {{ obtenerTextoImportancia(alerta.data.evento.importancia) }}
                                    </div>
                                </div>
                                <div class="header">{{ alerta.data.evento.titulo }}</div>
                                <div class="meta">Estado: {{ alerta.data.evento.estado_control }}</div>
                                <div class="meta">Aula: {{ alerta.data.evento.aula }}</div>
                                <div class="description">
                                    <!--<div class="event">
                                        <div class="content">
                                            <div class="description"></div>
                                        </div>
                                        <div class="content">
                                            <div class="description"></div>
                                        </div>
                                    </div>-->
                                    <p>Fecha: {{ alerta.data.evento.fecha }}</p>
                                    <p>Hora: {{ alerta.data.evento.hora }}</p>
                                    <p>Descripcion: {{ alerta.data.evento.descripcion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="item">
                        <div class="ui card">
                            <div class="content">
                                <div class="header">Elliot Fu</div>
                                <div class="meta">Amigo</div>
                                <div class="description">Elliot Fu is a film-maker from New York. </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui card">
                            <div class="content">
                                <div class="header">Elliot Fu</div>
                                <div class="meta">Amigo</div>
                                <div class="description">Elliot Fu is a film-maker from New York. </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui card">
                            <div class="content">
                                <div class="header">Elliot Fu</div>
                                <div class="meta">Amigo</div>
                                <div class="description">Elliot Fu is a film-maker from New York. </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </a>
        </portal>
    </div>
</template>

<script>
    export default {
        async mounted() {
            console.log("Montando!!");

            const ruta = 'App.User.' + this.usuario.toString();

            console.log("ruta", ruta);

            console.log("Echo", Echo);

            Echo.private('App.User.' + this.usuario.toString()).notification((notification) => {
                console.log(notification);
                $('#campana_notificacion').transition('tada', '1000ms').transition('set looping');
                //this.alertas.push(notification);
                const obj_event = {
                    data: {
                        evento: notification.evento
                    },
                    id: notification.id,
                    type: notification.type,
                };
                console.log("notificacion", notification);
                console.log("objeto: ", obj_event);
                //this.$set(this.alertas, this.alertas.length, obj_event);
                this.alertas.unshift(obj_event);
                $('#label_notificaciones').transition('bounce');
                this.nueva_notificacion = true;
            });

            const {data} = await axios.get("notificaciones");
            data.forEach((valor) => {

                console.log("valor", valor);
                this.alertas.push(valor);
            });

            if (this.alertas.length > 0) {
                this.nueva_notificacion = true;
                $('#campana_notificacion').transition('tada', '1000ms').transition('set looping');
                $('#label_notificaciones').transition('bounce');
            }

            console.log("Alertas", this.alertas);
        },
        props: {
            usuario: {type: Number, required: false}
        },
        data() {
            return {
                alertas: [],
                nueva_notificacion: false
            }
        },
        computed: {
            numNotificaciones() {
                return this.alertas.length;
            },

            actual_alertas() {
                return this.alertas;
            }
        },
        methods: {
            obtenerColorPrioridad(prioridad) {
                //Por defecto el color verde de la prioridad baja
                let color = "#69f0ae";
                switch (prioridad) {
                    case 1:
                        //Prioridad Alta
                        color = '#c4001d';
                        break;
                    case 2:
                        //Prioridad Media
                        color = "#ffff72";
                        break;
                }

                return color;
            },

            obtenerColorTexto(prioridad) {
                //Por defecto el color verde de la prioridad baja
                let color = "black";
                if (prioridad === 1) {
                    color = "white";
                }
                return color;
            },

            obtenerTextoImportancia(prioridad) {
                //Por defecto el color verde de la prioridad baja
                let texto = "Baja";
                switch (prioridad) {
                    case 1:
                        //Prioridad Alta
                        texto = "Alta";
                        break;
                    case 2:
                        //Prioridad Media
                        texto = "Media";
                        break;
                }

                return texto;
            },

            leerNotificaciones() {
                $('#campana_notificacion').transition('remove looping');
                this.nueva_notificacion = false;
            },

            marcarNotificacion(alerta) {
                console.log("id_notificacion: ", alerta.id);
                //console.log("item", $('.item.notification.' + this.alertas.indexOf(alerta)));
                let node = $('.item.notification.' + this.alertas.indexOf(alerta));
                node.transition({
                    animation: 'fly left',
                    duration: '500ms',
                    onComplete: async () => {
                        console.log("position: ", this.alertas.indexOf(alerta));
                        this.alertas.splice(this.alertas.indexOf(alerta), 1);
                        node.removeClass("transition").removeClass("hidden");
                        /* this.alertas.splice(this.alertas[key], 1);*/
                        this.$forceUpdate();
                        await axios.get("marcar/notificacion/" + alerta.id);
                    }
                });
            }
        }
    }
</script>

<style scoped>
    #label_notificaciones {
        position: absolute;
        z-index: 100;
        left: 70%;
        margin: 10px 0 0 0 !important;
        top: 0em;
    }
</style>
