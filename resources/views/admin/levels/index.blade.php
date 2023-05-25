@extends('admin.layout.main')

@section('title') Administrar Niveles de Conductores @endsection

@section('icon') mdi-account-clock @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 m-b-30">

                    <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                            <a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
                                Agregar Nuevo
                            </a>&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th> 
                                    <th>Valor maximo de pedido</th>
                                    <th>Servicios requeridos</th>
                                    <th>Descripción</th>
                                    <th style="text-align: right">Opciones</th>
                                </tr> 
                            </thead>
                            <tbody>

                            @foreach($data as $row)
                            <tr>
                                <td>#{{ $row->id }}</td>
                                <td>{{ $row->name }}</td> 
                                <td>
                                    ${{ number_format($row->max_cash,2) }}
                                </td> 
                                <td>
                                    {{$row->nivel}}
                                </td> 
                                <td>{{ $row->descript }}</td>
                                <td style="text-align: right">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-primary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        
                                        <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                            <!-- Edit -->
                                            <li>
                                                <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                                                    Editar
                                                </a>
                                            </li>
                                            <!-- Delete -->
                                            <li>
                                                <a href="javascript::void()" class="dropdown-item" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')">
                                                    Eliminar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr> 
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

