<template>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="nombres">
                        Nombres
                    </label>
                    <input type="text" class="form-control" name="nombres" placeholder="Ingrese nombres" id="nombres" v-model="persona.nombres">
                    <small class="form-text text-danger" v-if="errors.nombres"
                           v-for="error in errors.nombres">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="ape_paterno">
                        Apellido Paterno
                    </label>
                    <input type="text" class="form-control" name="ape_paterno" placeholder="Ingrese apellido paterno" id="ape_paterno" v-model="persona.ape_paterno">
                    <small class="form-text text-danger" v-if="errors.ape_paterno"
                           v-for="error in errors.ape_paterno">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="ape_materno">
                        Apellido Materno
                    </label>
                    <input type="text" class="form-control" name="ape_materno" placeholder="Ingrese apellido materno" id="ape_materno" v-model="persona.ape_materno">
                    <small class="form-text text-danger" v-if="errors.ape_materno"
                           v-for="error in errors.ape_materno">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="dni">
                        Dni
                    </label>
                    <input type="text" class="form-control" name="dni" placeholder="Ingrese dni" id="dni" v-model="persona.dni">
                    <small class="form-text text-danger" v-if="errors.dni"
                           v-for="error in errors.dni">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="telefono">
                        Telefono
                    </label>
                    <input type="text" class="form-control" name="telefono" placeholder="Ingrese telefono" id="telefono" v-model="persona.telefono">
                    <small class="form-text text-danger" v-if="errors.telefono"
                           v-for="error in errors.telefono">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="mail">
                       Email
                    </label>
                    <input type="text" class="form-control" name="mail" placeholder="Ingrese email" id="mail" v-model="persona.mail">
                    <small class="form-text text-danger" v-if="errors.mail"
                           v-for="error in errors.mail">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="direccion">
                        Dirección
                    </label>
                    <input type="text" class="form-control" name="direccion" placeholder="Ingrese dirección" id="direccion" v-model="persona.direccion">
                    <small class="form-text text-danger" v-if="errors.direccion"
                           v-for="error in errors.direccion">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="state">
                        Estado
                    </label>
                    <select name="state" id="state" v-model="persona.state" class="form-control">
                        <option value="">Selecciona</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.state"
                           v-for="error in errors.state">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="tipo_persona_id">
                        Tipo de Persona
                    </label>
                    <select name="tipo_persona_id" id="tipo_persona_id" v-model="persona.tipo_persona_id" class="form-control">
                        <option value="">Selecciona</option>
                        <option v-for="tipoPersona in tiposPersonas" :value="tipoPersona.id">{{tipoPersona.descripcion}}</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.tipo_persona_id"
                           v-for="error in errors.tipo_persona_id">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="urbanizacion_id">
                        Urbanizacion
                    </label>
                    <select name="urbanizacion_id" id="urbanizacion_id" v-model="persona.urbanizacion_id" class="form-control">
                        <option value="">Selecciona</option>
                        <option v-for="urbanizacion in urbanizaciones" :value="urbanizacion.id">{{urbanizacion.descripcion}}</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.urbanizacion_id"
                           v-for="error in errors.urbanizacion_id">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="nivel_ciudadano_id">
                        Nivel Ciudadano
                    </label>
                    <select name="nivel_ciudadano_id" id="nivel_ciudadano_id" v-model="persona.nivel_ciudadano_id" class="form-control">
                        <option value="">Selecciona</option>
                        <option v-for="nivelCiudadano in nivelesCiudadanos" :value="nivelCiudadano.id">{{nivelCiudadano.descripcion}}</option>
                    </select>
                    <small class="form-text text-danger" v-if="errors.nivel_ciudadano_id"
                           v-for="error in errors.nivel_ciudadano_id">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['persona', 'errors'],
        data(){
          return {
              nivelesCiudadanos: [],
              tiposPersonas: [],
              urbanizaciones:[]
          }

        },
        mounted() {

            axios.get(route('ciudadano.all')).then((response) => {
                this.nivelesCiudadanos = response.data.nivelesCiudadanos;

            });

            axios.get(route('tipo-persona.all-sin-paginado')).then((response) => {
                this.tiposPersonas = response.data.tiposPersonas;
            });

            axios.get(route('urbanizacion.all-sin-paginado')).then((response) => {
                this.urbanizaciones = response.data.urbanizaciones;

            });

        },
        methods: {

        }
    }
</script>