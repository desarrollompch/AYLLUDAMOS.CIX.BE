<template>
    <div class="row">
        <div class="col-sm-12 table-responsive">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNotificacion">
                Notificaciones <span class="badge badge-light">{{notificaciones.length}}</span>
            </button>
        </div>
        <div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog" aria-labelledby="modalNotificacion"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNotificacionLabel">Notificaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="list-group">
                                    <li class="list-group-item" v-for="(notificacion, index) in notificaciones">
                                        <div class="row">
                                            <div class="col-sm-11">
                                                {{notificacion.descripcion}} (<a :href="getIncidenteUrl(notificacion.incidente_id)"> Ver</a>)
                                            </div>
                                            <div class="col-sm-1">
                                                <a href="#" class="text-danger float-right"
                                                   v-on:click="deleteNotificacion(notificacion, index)">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                notificaciones: []
            }
        },
        mounted() {
            axios.get(route('notificacion.index')).then((response) => {
                this.notificaciones = response.data.notificaciones;
            });
        },
        methods: {

            deleteNotificacion(notificacion, index){
                axios.delete(route('notificacion.destroy', {id: notificacion.id})).then((response) => {
                    if (response.data.success) {
                        this.notificaciones.splice(index, 1);
                    } else {
                        alert(response.data.message);
                    }
                }).catch((error) => {
                    console.log(error.data);
                });
            },
            getIncidenteUrl(id){
                return route('incidente.detalle', {id: id});
            }

        }
    }
</script>