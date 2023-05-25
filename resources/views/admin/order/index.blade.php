@extends('admin.layout.main')
@section('title') {{ $title }} @endsection
@section('icon') mdi-cart @endsection

@section('css')
    <style>
        .results tr[visible='false'],
        .no-result{
        display:none;
        }

        .results tr[visible='true']{
        display:table-row;
        }

        .counter{
        padding:8px; 
        color:#ccc;
        }
    </style>
@endsection

@section('content')
<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        <div class="form-group pull-right">
                            <input type="text" class="search form-control" placeholder="Realiza una busqueda rapida de elementos">
                        </div>
                        <span class="counter pull-right"></span>
                        <table class="table table-hover results">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Negocio</th>
                                    <th>Usuario</th>
                                    <th>Estado del pedido</th>
                                    <th>Elementos</th>
                                    <th style="text-align: right">Opciones</th>
                                </tr>
                                <tr class="warning no-result">
                                    <td colspan="4"><i class="fa fa-warning"></i> Sin Resultados</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                @include('admin.order.viewElements')
                                <tr>

                                    <td width="2%">#{{ $row->id }}</td>
                                    <td width="5%">{{ $row->code_order }}</td>
                                    <td width="15%">{{ $row->store }}</td>
                                    <td width="15%">
                                        {{ $row->name }}<br>
                                        <small>Tel: {{ $row->phone }}</small>    
                                    </td>
                                    <td width="20%">	
                                        @if($row->InnStore == 1)
                                            <span>Pedido en mesa <b>#{{$row->mesa}}</b></span><br>
                                        @endif
                                        
                                        @if($row->status == 0 )
                                        <span class="badge badge-success">Pedido Nuevo</span>
                                        @elseif($row->status == 1)
                                        <span class="badge badge-primary">Pedido Aceptado</span>
                                        @elseif($row->status == 2)
                                        <span style="font-size: 12px">Cancelado a las <br>{{ $row->status_time }}</span>
                                        @elseif($row->status == 1.5)
                                        <span class="badge badge-secondary">Buscando Repartidor</span>
                                        @elseif($row->status == 3)
                                        <span class="badge badge-info">Repartidor asignado</span>
                                        <br />
                                        <small>Repartidor(a): {{$row->dboy}}</small>
                                        @elseif($row->status == 4)
                                        <span class="badge badge-warning">En ruta al destino</span>
                                        <br />
                                        <small>Repartidor(a): {{$row->dboy}}</small>
                                        @elseif($row->status == 5 || $row->status == 6)
                                            @if($row->InnStore == 0)
                                            <span style="font-size: 12px">
                                                Entregado por<br /> 
                                                {{ $row->dboy }} a las:<br>{{ $row->status_time }}
                                            </span>
                                            @endif
                                        @endif  
                                    </td>
                                    <td width="20%">	
                                        <button href="javascript::void()" data-toggle="modal" data-target="#viewModalElements{{ $row->id }}" class="btn btn-secondary">
                                            Vista de elementos
                                        </button>
                                    </td>

                                    <td width="23%">
                                        @include('admin.order.action')
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {!! $data->appends(request()->except('page'))->links() !!}
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $(".search").keyup(function () {
                var searchTerm = $(".search").val();
                var listItem = $('.results tbody').children('tr');
                var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
                    
                $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                    console.log(elem);
                        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                    }
                });
                    
                $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
                    $(this).attr('visible','false');
                });

                $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
                    $(this).attr('visible','true');
                });

                var jobCount = $('.results tbody tr[visible="true"]').length;
                    $('.counter').text(jobCount + ' Elemento(s)');

                if(jobCount == '0') {$('.no-result').show();}
                    else {$('.no-result').hide();}
            });
        });
    </script>
@endsection