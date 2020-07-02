<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Jefe de RRHH</li>
                <li class="breadcrumb-item">Mantenimiento</li>
                <li class="breadcrumb-item">Gestión de Puestos y Salarios</li>
                <li class="breadcrumb-item active">Puestos</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Puestos
                        <button @click="abrirModal('puesto', 'registrar')" type="button" class="btn btn-success btn-nuevo">
                            <i class="icon-plus"></i> <span style="margin-left:2%">Nuevo</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="codigo_puesto">Código</option>
                                      <option value="nombre">Nombre</option>
                                      <option value="nombreu">Unidad Org.</option>
                                      <option value="salario_minimo">Salario mínimo</option>
                                      <option value="salario_maximo">Salario máximo</option>
                                      <!-- <option value="todos">Todos</option> -->
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPuestos(1, buscar, criterio)" class="form-control col-md-5" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPuestos(1, buscar, criterio)" class="btn btn-primary btn-buscar"><i class="fa fa-search"></i> Buscar</button>
                                </div>             
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Código</th>
                                    <th>Nombre Puesto</th>
                                    <th>Unidad / Depto.</th>
                                    <th>Salario Mínimo</th>
                                    <th>Salario Máximo</th>
                                    <th style="text-align: center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="puesto in arrayPuesto" :key="puesto.codigo_unidad_organizativa">
                                    <td v-text="puesto.p_id" style="text-align: center"></td>
                                    <td v-text="puesto.nombrepuesto"></td>
                                    <td>{{puesto.nombreunidad}}</td>
                                    <td v-text="puesto.cs_min" style="text-align: right"></td>
                                    <td v-text="puesto.cs_max" style="text-align: right"></td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('puesto', 'actualizar', puesto)" class="btn btn-warning btn-sm btn-circle-text-white">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModalEliminar(puesto)" class="btn btn-danger btn-sm btn-circle">
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
                            <!-- CAMBIAR PARA GENERAR EL CÓDIGO DE FORMA AUTOMÁTICA -->
                                <div v-if="tipoAccion == 1" class="form-group">
                                    <label for="p_id">Código de Puesto</label>
                                    <input type="text" v-model="p_id" class="form-control" maxlength="6" placeholder="AAAAAA">
                                </div>
                                <div v-if="tipoAccion == 2" class="form-group">
                                    <label for="p_id">Código de Puesto</label>
                                    <input type="text" v-model="p_id" class="form-control" style="font-style:italic; border:0; border-radius:6px; background: #eee" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="nombrePuesto">Nombre</label>
                                    <input type="Text" v-model="nombrePuesto" class="form-control" maxlength="25" placeholder="Ingrese el nombre del puesto">
                                </div>
                                <div class="form-group">
                                    <label for="descPuesto">Descripción</label>
                                    <textarea class="form-control" rows="3" maxlength="150" v-model="descPuesto" placeholder="Ingrese la descripción del puesto"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="idUnidad">Unidad a la que pertenece</label>
                                    <select class="form-control" v-model="p_idUnidad" name="p_idUnidad">
                                        <option value="" disabled>-- Seleccione la Unidad --</option>
                                        <option v-for="u in arrayUnidad" :key="u.codigo_unidad_organizativa" v-bind:value="u.codigo_unidad_organizativa">{{u.nombre}}</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cs_min">Salario Mínimo ($)</label>
                                    <input type="number" step="0.01" min="0" v-model="cs_min" class="form-control" placeholder="00.00">
                                </div>
                                <div class="form-group">
                                    <label for="cs_max">Salario Máximo ($)</label>
                                    <input type="number" step="0.01" min="0" v-model="cs_max" class="form-control" placeholder="00.00">
                                </div>
                                                                    
                                <div v-show="errorPuesto" class="form-group div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPuesto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarPuesto()" class="btn btn-primary btn-circle">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarPuesto()"  class="btn btn-primary btn-circle">Actualizar</button>
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
                            <h4 class="modal-title">Eliminar Puesto</h4>
                            <button type="button" class="close" @click="cerrarModalEliminar()" aria-label="Close">
                              <span aria-hidden="true" style="color:white">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estas seguro de eliminar el puesto? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-circle-text-white" @click="cerrarModalEliminar()">Cerrar</button>
                            <button type="button" class="btn btn-danger btn-circle" @click="eliminarPuesto(p_id)">Eliminar</button>
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
                //Data de tabla puesto
                p_id:'',
                nombrePuesto:'',
                descPuesto:'',
                p_idUnidad:'',
                //Data de Categoría Salarial
                cs_id:0,
                cs_min:0,
                cs_max:0,                
                cs_isr: 0,//id_catalogo_isr:1,
                //Data de Unidad Organizativa
                idUnidad : '',
                nombreUnidad:'',
                arrayUnidad: [],
                //Data general
                arrayPuesto: [],
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorPuesto: 0,
                errorMostrarMsjPuesto: [],
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
            listarPuestos(page, buscar, criterio){
                let me=this;
                var lengthbuscar = this.buscar.length;
                if(lengthbuscar >0)
                {
                    var buscar2=this.buscar.toUpperCase();
                }else
                buscar2=this.buscar;

                if(criterio=='nombre'){
                    var url='/puesto?page=' + page + '&buscar=' + buscar2 + '&criterio=' + criterio;
                    axios.get(url).then(function (response){
                        var respuesta=response.data;
                        me.arrayPuesto=respuesta.puestos.data;
                        console.log(me.arrayPuesto);
                        me.pagination=respuesta.pagination;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }else{
                    url='/puesto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response){
                        var respuesta=response.data;
                        me.arrayPuesto=respuesta.puestos.data;
                        console.log(me.arrayPuesto);
                        me.pagination=respuesta.pagination;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
                /* if(criterio=='todos'){ criterio='codigo_puesto'; buscar='';}

                let me=this;
                var url= '/puesto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                .then(function (response) {
                    var respuesta= response.data;
                    me.arrayPuesto = respuesta.puestos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                }); */
            },

            //Seleccionar Unidad Organizacional
            selectUnidad(){
                let me=this;
                var url='/unidadOrganizacional/selectUnidades';
                axios.get(url).then(function (response) {
                me.arrayUnidad = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            //PAGINACION: CAMBIO DE PAGINA
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPuestos(page, buscar, criterio);
            },

            //REGISTRAR OBJETO
            registrarPuesto(){
                if (this.validarPuesto()){
                    return;
                }
                if (this.cs_min<=4064/12){
                    this.cs_isr=1;  
                }
                else{
                    if (this.cs_min<=9142.86/12){
                        this.cs_isr=2;
                    }
                    else{
                        if(this.cs_min<=22857.14/12){
                            this.cs_isr=3;
                        }
                        else{
                            this.cs_isr=4;
                        }
                    }
                }

                let me=this;
                axios.post('/puesto/registrar', {
                    'p_id': this.p_id,
                    'nombrePuesto': this.nombrePuesto,
                    'descPuesto': this.descPuesto,
                    'p_idUnidad': this.p_idUnidad,
                    'cs_id': this.cs_id,
                    'cs_min': this.cs_min,
                    'cs_max': this.cs_max,
                    'cs_isr':this.cs_isr,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPuestos(1, '', 'p_id');//Antes decía nombre
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //ACTUALIZAR OBJETO
            actualizarPuesto(){
                if (this.validarPuesto()){
                    return;
                }
                if (this.cs_min<=4064/12){
                    this.cs_isr=1;  
                }
                else{
                    if (this.cs_min<=9142.86/12){
                        this.cs_isr=2;
                    }
                    else{
                        if(this.cs_min<=22857.14/12){
                            this.cs_isr=3;
                        }
                        else{
                            this.cs_isr=4;
                        }
                    }
                }

                let me=this;
                axios.put('/puesto/actualizar', {
                    'p_id': this.p_id,
                    'nombrePuesto': this.nombrePuesto,
                    'descPuesto': this.descPuesto,
                    'p_idUnidad': this.p_idUnidad,
                    'cs_id': this.cs_id,
                    'cs_min': this.cs_min,
                    'cs_max': this.cs_max,
                    'cs_isr':this.cs_isr,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarPuestos(1, '', 'p_id');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //VALIDACION DE CAMPOS
            validarPuesto(){
                this.errorPuesto = 0;
                this.errorMostrarMsjPuesto = [];

                if (!this.p_id) this.errorMostrarMsjPuesto.push("Debe establecer un código de Puesto.");
                if (!this.nombrePuesto) this.errorMostrarMsjPuesto.push("Debe establecer un nombre de Puesto.");
                if (!this.p_idUnidad) this.errorMostrarMsjPuesto.push("Debe seleccionar la Unidad Organizativa del Puesto.");
                if (!this.cs_min || this.cs_min<=0) this.errorMostrarMsjPuesto.push("La cantidad mínima de salario debe ser mayor a 0.");
                if (!this.cs_max || this.cs_max<=0) this.errorMostrarMsjPuesto.push("La cantidad máxima de salario debe ser mayor a 0.");
                if (this.cs_min >= this.cs_max) this.errorMostrarMsjPuesto.push("La cantidad máxima debe ser mayor que la mínima");
                //Estas validaciones son para mantener un solo rango de catalogo_isr (ASUMO QUE SERÁ INGRESO MENSUAL)
                if (this.cs_min<=4064/12){
                    if (this.cs_max>4064/12){
                        this.errorMostrarMsjPuesto.push("El rango salarial debe pertenecer a una misma categoria ISR.");
                    }
                }
                else{
                    if (this.cs_min<=9142.86/12){
                        if (this.cs_max>9142.86/12){
                            this.errorMostrarMsjPuesto.push("El rango salarial debe pertenecer a una misma categoria ISR.");
                        }
                    }
                    else{
                        if (this.cs_min<=22857.14/12){
                            if (this.cs_max>22857.14/12){
                                this.errorMostrarMsjPuesto.push("El rango salarial debe pertenecer a una misma categoria ISR.");
                            }
                        }
                        else{
                            if (this.cs_max<=22857.14/12){
                                this.errorMostrarMsjPuesto.push("El rango salarial debe pertenecer a una misma categoria ISR.");
                            }
                        }
                    }
                }
                /* if (!this.venMin || this.venMin<=0) this.errorMostrarMsjPuesto.push("La cantidad mínima de salario debe ser mayor a 0."); */

                if (this.errorMostrarMsjPuesto.length) this.errorPuesto = 1;
                return this.errorPuesto;
            },

            //CERRAR MODAL
            cerrarModal(){
                this.modal = 0;
                this.p_id = 0;
                this.nombrePuesto = '';
                this.descPuesto = '';
                this.p_idUnidad = '';
                this.cs_id = 0;
                this.cs_min = '';
                this.cs_max = '';
                this.idUnidad = '';
                this.cs_isr = 0;
                this.tituloModal = '';
                this.errorMostrarMsjPuesto = [];
                this.errorPuesto = 0;
            },

            //ABRIR MODAL REGISTRAR/ACTUALIZAR
            abrirModal(modelo, accion, data = []){
            switch(modelo)
            {
                case "puesto":
                    {
                        switch(accion){
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.p_id = "";
                                    this.nombrePuesto = '';
                                    this.descPuesto = '';
                                    this.p_idUnidad = '';
                                    this.cs_id = 0;
                                    this.cs_min = '';
                                    this.cs_max = '';
                                    this.idUnidad = '';
                                    this.cs_isr = 0;
                                    this.tituloModal = 'Registrar Puesto';
                                    this.tipoAccion = 1;
                                    break
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.p_id = data['p_id'];
                                    this.nombrePuesto = data['nombrepuesto'];
                                    this.descPuesto = data['descpuesto'];
                                    this.p_idUnidad = data['p_idunidad'];
                                    this.cs_id = data['cs_id'];
                                    this.cs_min = data['cs_min'];
                                    this.cs_max = data['cs_max'];                
                                    this.cs_isr = data['cs_isr'];
                                    this.tituloModal = 'Actualizar Puesto';
                                    this.tipoAccion = 2;
                                    break
                                }
                        }
                    }
            }
        },

        //ELIMINAR Puesto
        eliminarPuesto(p_id){
            let me=this;
            axios.put('/puesto/eliminar', {
                'p_id': this.p_id,
                'cs_id': this.cs_id,
            })
            .then(function (response) {
                me.cerrarModalEliminar();
                me.listarPuestos(1, '', 'p_id');
            }).catch(function (error) {
                console.log(error);
            });
        },
        
        //ABRIR MODAL ELIMINAR
        abrirModalEliminar(data = []){
            this.modalEliminar = 1;
            this.nombrePuesto = data['nombrePuesto'];
            this.p_id = data['p_id'];
            this.cs_id = data['cs_id'];
        },

        //CERRAR MODAL
        cerrarModalEliminar(){
            this.modalEliminar = 0;
            this.nombrePuesto = '';
            this.p_id = 0;
            this.cs_id = 0;
        },

    },

    //PARTE DEL CICLO DE VIDA DE VUE
    mounted() {
        this.listarPuestos(1, this.buscar, this.criterio);
        this.selectUnidad();
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
