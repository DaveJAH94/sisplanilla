<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Jefe de RRHH</li>
                <li class="breadcrumb-item">Mantenimiento</li>
                <li class="breadcrumb-item active">Unidades Organizacionales</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Unidades Organizacionales
                        <button @click="abrirModal('unidadOrganizacional', 'registrar')" type="button" class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <!-- <option value="codigo_unidad_organizativa">Código</option> -->
                                      <option value="nombre">Nombre</option>
                                      <option value="codigo_unidad_organizativa">Código Unidad</option>
                                      <option value="nombre_sup">Unidad Superior</option>
                                      <!-- <option value="limites">Límites presupuestarios</option> -->
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarUnidadesOrganizacionales(1, buscar, criterio)" class="form-control col-md-5" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUnidadesOrganizacionales(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>             
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Código</th>
                                    <th>Nombre</th>
                                    <!-- <th>Descripción</th> -->
                                    <th>Unidad Superior</th>
                                    <th>Límites presupuestarios</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="unidad_organizacional in arrayUnidadOrganizacional" :key="unidad_organizacional.codigo_unidad_organizativa">
                                    <td v-text="unidad_organizacional.id_unidad" style="text-align: center"></td>
                                    <td v-text="unidad_organizacional.nombre_unidad"></td>
                                    <!-- <td v-text="unidad_organizacional.desc_unidad"></td> -->
                                    <td v-text="unidad_organizacional.uni_superior"></td>
                                    <td>De ${{unidad_organizacional.lim_min}} a ${{unidad_organizacional.lim_max}}</td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('unidadOrganizacional', 'actualizar', unidad_organizacional)" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalEliminar(unidad_organizacional)" class="btn btn-danger btn-sm btn-circle">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; overflow-y:auto;" aria-hidden="true">
                <div class="modal-dialog modal-primary" role="document" style="border-radius: 4px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        
                        <div class="modal-body" style="padding:4%">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                                <div v-if="tipoAccion == 1" class="form-group">
                                    <label for="id_unidad">Código de Unidad</label>
                                    <input type="text" v-model="id_unidad" class="form-control" maxlength="4" placeholder="AAAA">
                                </div>
                                <div v-if="tipoAccion == 2" class="form-group">
                                    <label for="id_unidad">Código de Unidad</label>
                                    <input type="text" v-model="id_unidad" class="form-control" style="font-style:italic; border:0; border-radius:6px; background: #eee" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="nombre_unidad">Nombre</label>
                                    <input type="text" v-model="nombre_unidad" class="form-control" maxlength="30" placeholder="Nombre Unidad Organizacional">
                                </div>
                                <div class="form-group">
                                    <label for="desc_unidad">Descripción</label>
                                    <textarea class="form-control" rows="3" maxlength="150" v-model="desc_unidad" placeholder="Ingrese descripción de la Unidad Organizativa"></textarea>
                                </div>
                                <div v-if="tipoAccion == 1" class="form-group">
                                    <label for="sup_unidad">Unidad Superior</label>
                                    <select id="cus_input_d" v-model="sup_unidad" class="form-control">
                                        <option value="" selected>-- Seleccione Unidad Superior --</option>
                                        <option v-for="uSup in arrayUnidadSuperior" :key="uSup.id_superior"
                                        v-bind:value="uSup.id_superior" v-text="uSup.uni_superior"></option>
                                    </select>
                                </div>
                                <div v-if="tipoAccion == 2" class="form-group">
                                    <label for="sup_unidad">Unidad Superior</label>
                                    <select id="cus_input_d" v-model="sup_unidad" class="form-control" style="font-style:italic; border:0; border-radius:6px; background: #eee" disabled>
                                        <option value="" selected>-- Seleccione Unidad Superior --</option>
                                        <option v-for="uSup in arrayUnidadSuperior" :key="uSup.id_superior"
                                        v-bind:value="uSup.id_superior" v-text="uSup.uni_superior"></option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                  <label class="col-md-5 form-control-label" for="text-input"><b>Límite Presupuestario</b></label>
                                </div>
                                <div class="form-group">
                                    <label for="lim_min">Mínimo</label>
                                    <input type="number" step="0.1" min="0" v-model="lim_min" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="lim_max">Máximo</label>
                                    <input type="number" step="0.1" min="0" v-model="lim_max" class="form-control" placeholder="00.00">
                                </div>

                                <div v-show="errorUnidadOrganizacional" class="form-group div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUnidadOrganizacional" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarUnidadOrganizacional()" class="btn btn-primary btn-circle">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarUnidadOrganizacional()"  class="btn btn-primary btn-circle">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" :class="{'mostrar' : modalEliminar}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Unidad Organizacional</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estas seguro de eliminar la unidad organizacional? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarUnidadOrganizacional(id_unidad)">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data(){
            return{
                //Unidad Organizacional
                id_unidad:'',
                sup_unidad:'',
                nombre_unidad:'',
                desc_unidad:'',
                //Unidad Superior (Para listar)
                id_superior:'',
                uni_superior:'',//Nombre unidad superior
                //Límite presupuestario
                lp_id:0,
                lp_unidad:'',
                lim_min:0,
                lim_max:0,
                //listar Unidades Org en tabla
                arrayUnidadOrganizacional: [],
                //listar Unidades Org en combobox
                arrayUnidadSuperior: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorUnidadOrganizacional: 0,
                errorMostrarMsjUnidadOrganizacional: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,                    
                },
                offset: 3,
                criterio: 'nombre',
                buscar: '',
                modalEliminar: 0
            }
        },

        //LOGICA DE PAGINACION
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },

        //METODOS UTILIZADOS PARA ACCIONAR BOTONES Y VISUALIZAR MODALS
        methods: {

            //LISTAR OBJETOS
            listarUnidadesOrganizacionales(page, buscar, criterio){
                //if(criterio=='todos'){ criterio='id_unidad'; buscar='';}
                let me=this;
                var lengthbuscar = this.buscar.length;
                 if(lengthbuscar >0)
                 {
                     var buscar2= this.buscar.toUpperCase();
                 }else
                 buscar2=this.buscar;

                var url= '/unidadOrganizacional?page=' + page + '&buscar=' + buscar2 + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayUnidadOrganizacional = respuesta.unidades_organizacionales.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            selectUnidadSuperior(){
                let me=this;
                var url= '/unidadOrganizacional/selectUnidadSuperior';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUnidadSuperior = respuesta.unidades_superiores;
                })
            },

            //PAGINACION: CAMBIO DE PAGINA
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarUnidadesOrganizacionales(page, buscar, criterio);
            },

            //REGISTRAR OBJETO
            registrarUnidadOrganizacional(){
                if (this.validarUnidadOrganizacional()){
                    return;
                }

                let me=this;
                axios.post('/unidadOrganizacional/registrar', {
                    'id_unidad': this.id_unidad,
                    'sup_unidad': this.sup_unidad,
                    'nombre_unidad': this.nombre_unidad,
                    'desc_unidad': this.desc_unidad,

                    'lp_id': this.lp_id,
                    'lp_unidad': this.id_unidad,
                    'lim_min': this.lim_min,
                    'lim_max': this.lim_max
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarUnidadesOrganizacionales(1, '', 'codigo_unidad_organizativa');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //ACTUALIZAR OBJETO
            actualizarUnidadOrganizacional(){
                if (this.validarUnidadOrganizacional()){
                    return;
                }

                let me=this;
                axios.put('/unidadOrganizacional/actualizar', {
                    'sup_unidad': this.sup_unidad,
                    'nombre_unidad': this.nombre_unidad,
                    'desc_unidad': this.desc_unidad,
                    'id_unidad': this.id_unidad,
                    
                    'lp_id': this.lp_id,
                    'lp_unidad': this.lp_unidad,
                    'lim_min': this.lim_min,
                    'lim_max': this.lim_max
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarUnidadesOrganizacionales(1, '', 'nombre_unidad');//Antes decía 'Nombre'
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //VALIDACION DE CAMPOS
            validarUnidadOrganizacional(){

                var cus_input = document.getElementById("cus_input_d");

                this.errorUnidadOrganizacional = 0;
                this.errorMostrarMsjUnidadOrganizacional = [];

                if (!this.nombre_unidad) this.errorMostrarMsjUnidadOrganizacional.push("Debe asignar el nombre de la unidad");
                if (!this.desc_unidad) this.errorMostrarMsjUnidadOrganizacional.push("Debe agregar una descripcion");

                if (!this.sup_unidad || cus_input.value.length == 0 || cus_input.value === 0 || cus_input.value === '0'){
                    cus_input.style.border = "1px solid red";
                    this.errorMostrarMsjUnidadOrganizacional.push("La unidad Organizativa debe tener una Unidad Superior");
                }else{
                    cus_input.style.border= "1px solid #ccc";
                }
                if (!this.lim_min || this.lim_min<=0) this.errorMostrarMsjUnidadOrganizacional.push("El límite presupuestario mínimo debe de ser mayor a 0.");
                if (!this.lim_max || this.lim_max<=0) this.errorMostrarMsjUnidadOrganizacional.push("El límite presupuestario máximo debe de ser mayor a 0.");

                if (this.lim_min >= this.lim_max) this.errorMostrarMsjUnidadOrganizacional.push("El límite máximo debe de ser mayor que el mínimo");
                
                if (this.errorMostrarMsjUnidadOrganizacional.length) this.errorUnidadOrganizacional = 1;

                return this.errorUnidadOrganizacional;
            },

            //CERRAR MODAL
            cerrarModal(){
                this.modal = 0;
                this.id_unidad = 0;
                this.nombre_unidad = '';
                this.desc_unidad = '';
                this.sup_unidad = '';
                this.lp_id = 0;
                this.lp_unidad = '';
                this.lim_min = '';
                this.lim_max = '';
                this.tituloModal = '';
                this.errorMostrarMsjUnidadOrganizacional = [];
                this.errorUnidadOrganizacional = 0;
                if(document.getElementById("cus_input_d")!=null) document.getElementById("cus_input_d").style.border = "1px solid #ccc";
            },

            //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "unidadOrganizacional":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.id_unidad = '';
                                    this.nombre_unidad = '';
                                    this.desc_unidad = '';
                                    this.sup_unidad = '';
                                    this.lp_id = 0;
                                    this.lp_unidad = '';
                                    this.lim_min = '';
                                    this.lim_max = '';
                                    this.tituloModal = 'Registrar Unidad Organizacional';
                                    this.tipoAccion = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.nombre_unidad = data['nombre_unidad'];
                                    this.desc_unidad = data['desc_unidad'];
                                    this.sup_unidad = data['sup_unidad'];
                                    this.id_unidad = data['id_unidad'];
                                    this.lp_id = data['lp_id'];
                                    this.lp_unidad = data['lp_unidad'];
                                    this.lim_min = data['lim_min'];
                                    this.lim_max = data['lim_max'];
                                    this.tituloModal = 'Actualizar Unidad Organizacional';
                                    this.tipoAccion = 2;
                                    break;
                                }
                        }
                    }
                this.selectUnidadSuperior();
            }
        },

        //ELIMINAR Unidad Organizacional
        eliminarUnidadOrganizacional(id_unidad){
            let me=this;
            axios.put('/unidadOrganizacional/eliminar', {
                'id_unidad': this.id_unidad,
                'lp_id': this.lp_id,
            })
            .then(function (response) {
                me.cerrarModalEliminar();
                me.listarUnidadesOrganizacionales(1, '', 'nombre_unidad');
            }).catch(function (error) {
                console.log(error);
            });
        },
        
        //ABRIR MODAL ELIMINAR
        abrirModalEliminar(data = []){
            this.modalEliminar = 1;
            this.nombre_unidad = data['nombre_unidad'];
            this.id_unidad = data['id_unidad'];
            this.lp_id = data['lp_id'];
        },

        //CERRAR MODAL
        cerrarModalEliminar(){
            this.modalEliminar = 0;
            this.nombre_unidad = '';
            this.id_unidad = '';
            this.lp_id = 0;
        },

    },

    //PARTE DEL CICLO DE VIDA DE VUE
    mounted() {
        this.listarUnidadesOrganizacionales(1, this.buscar, this.criterio);
        this.selectUnidadSuperior('','');
    }
    }
</script>

<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: normal !important;
        font-style:italic;
    }
    .btn-circle{
        border-radius: 4px;
    }
    .btn-circle-text-white{
        border-radius: 4px; color:white;
    }
    .btn-nuevo{
        margin-left:1%;
        margin-top:0.2% !important; 
        border-radius: 6px; 
        color:white !important;
    }
    .btn-buscar{
        border-top-right-radius:4px;
        border-bottom-right-radius:4px;
    }
</style>
