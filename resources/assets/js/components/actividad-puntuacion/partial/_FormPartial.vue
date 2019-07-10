<template>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="estado_incidente_id">Estado Incidente</label>
                <select name="estado_incidente_id" id="estado_incidente_id" class="form-control"
                        v-model="actividadPuntuacion.estado_incidente_id">
                    <option value="">Selecione..</option>
                    <option v-for="estadoIncidente in estadosIncidentes" :value="estadoIncidente.id">
                        {{estadoIncidente.descripcion}}
                    </option>
                </select>
                <small class="form-text text-danger" v-if="errors.estado_incidente_id"
                       v-for="error in errors.estado_incidente_id">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input type="text" id="descripcion" class="form-control" placeholder="Ingrese una descripción"
                       name="descripcion" v-model="actividadPuntuacion.descripcion">
                <small class="form-text text-danger" v-if="errors.descripcion"
                       v-for="error in errors.descripcion">
                    {{error}}
                </small>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label for="puntaje">Puntaje</label>
                <input type="text" id="puntaje" class="form-control" placeholder="Puntaje"
                       name="puntaje" v-model="actividadPuntuacion.puntaje">
                <small class="form-text text-danger" v-if="errors.puntaje"
                       v-for="error in errors.puntaje">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['actividadPuntuacion', 'errors'],
        data() {
            return {
                estadosIncidentes: []
            }
        },
        mounted() {
            axios.get(route('estado-incidente.all')).then((response) => {
                this.estadosIncidentes = response.data.estadosIncidentes;
            });
        }
    }
</script>