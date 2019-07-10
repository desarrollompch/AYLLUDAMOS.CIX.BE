<template>
    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="descripcion">
                    Descripcion
                </label>
                <input type="text" class="form-control" name="descripcion" placeholder="Ingrese descripcion" id="descripcion" v-model="coordinacion.descripcion">
                <small class="form-text text-danger" v-if="errors.descripcion"
                       v-for="error in errors.descripcion">
                    {{error}}
                </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['coordinacion', 'errors']
    }
</script>