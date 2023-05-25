@extends('admin.layout.main')

@section('title') Administrar categorías @endsection

@section('icon') mdi-silverware-fork-knife @endsection


@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-md-12">
<div class="card py-3 m-b-30">

<div class="row">
<div class="col-md-12" style="text-align: right;"><a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-warning">Add New</a>&nbsp;&nbsp;&nbsp;</div>

</div>


<div class="card-body">
<table class="table table-hover ">
<thead>
<tr> 
<th>Imagen</th>
<th>Nombre</th>
<th>Tipo</th>
<th>Orden</th>
<th>Status</th>
<th style="text-align: right">Opciones</th>
</tr>

</thead>
<tbody>

@foreach($data as $row)

<tr> 
    <td>
        <img src="{{ Asset('upload/categorys/'.$row->img) }}" width="50" alt="Icon Category">
    </td>
    <td>
        {{ $row->name }}
        <br />
        @if($row->type_cat == 0)
        <small>(Principal)</small>
        @elseif($row->type_cat == 1)
        <small>({{$cats->getCatID($row->id_cp)}})</small>
        @else 
        <small>({{$cats->getCatID($row->id_cp)}} > {{$cats->getCatID($row->id_c)}})</small>
        @endif
    </td>
    <td>
        @if($row->type_cat == 0)
        Principal
        @elseif($row->type_cat == 1)
        Categoria
        @else 
        SubCategoria
        @endif
    </td>
    <td>{{ $row->sort_no }}</td>
    <td>
        @if($row->status == 0)
            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
        @else
            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
        @endif
    </td>
    <td style="text-align: right">
        <a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="mdi mdi-border-color"></i></a>
        <button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="mdi mdi-delete-forever"></i></button>
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