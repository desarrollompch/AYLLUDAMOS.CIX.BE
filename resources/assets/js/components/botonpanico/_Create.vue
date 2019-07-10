<template>
  <div class="card">
    <div class="card-body">
      <div v-if="botonpanico" class="col-md-12 text-center">
        <toggle-button v-bind:value="botonpanico.state"
        @change="newBotonPanico"
        v-model="botonpanico.state"
        :labels="{checked: 'ACTIVO', unchecked: 'INACTIVO'}"
        :width = 130
        :height = 50
        />
      </div>
    </div>
  </div>
</template>

<script>
  import ToggleButton from 'vue-js-toggle-button'
  Vue.use(ToggleButton)
  export default {
    data(){
     return {
      estadoBP: '',
      botonpanico: null,
      botonpanicoAux : {
        state : false
      },
      errors: [],
      loading:false,
      cancel_url: route('botonpanico.index')
     }
    },
    mounted(){
      this.getBotonPanico();
    },
    methods: {
      getBotonPanico(){
        axios.get(route('botonpanico.getBotonPanico')).then((response) =>{
          if(response.data.botonpanico==null){
            this.botonpanico = this.botonpanicoAux;
          }else{
            this.botonpanico = response.data.botonpanico;
            this.botonpanico.state = (this.botonpanico.state == 1) ? true : false;
          }
          console.log(this.botonpanico);
          $('#mute').removeClass('on');
        })
        .catch((error) =>{
          if(error.response.status == 422)
          {
            this.errors = error.response.data.errors;
            $('#mute').removeClass('on');
          }
        })
      },
      newBotonPanico(){
        this.loading = true;
        console.log(this.botonpanico);
        axios.post(route('botonpanico.store'), this.botonpanico).then((response) =>{
          this.loading = false;
        }).catch((error) =>{
          if(error.response.status == 422)
          {
            this.errors = error.response.data.errors;
          }
          this.loading = false;
        })
      }

    }
  }
</script>