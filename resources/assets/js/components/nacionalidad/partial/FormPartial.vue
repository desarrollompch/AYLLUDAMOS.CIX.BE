<template>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="descripcion">Nacionalidad</label>
                    <input type="text" id="descripcion" class="form-control" placeholder="Nacionalidad"
                           name="descripcion" v-model="nacionalidad.nacionalidad">
                    <small class="form-text text-danger" v-if="errors.nacionalidad"
                           v-for="error in errors.nacionalidad">
                        {{error}}
                    </small>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="cantidad_digitos">Cantidad de digitos</label>
                    <input type="text" id="cantidad_digitos" class="form-control" placeholder="Cantidad de digitos"
                           name="cantidad_digitos" v-model="nacionalidad.cantidad_digitos">
                    <small class="form-text text-danger" v-if="errors.cantidad_digitos"
                           v-for="error in errors.nacionalidad">
                        {{error}}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['nacionalidad', 'errors']
    }
</script>