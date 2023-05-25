@extends('user.layout.main')

@section('title') Administrar artículos @endsection

@section('icon') mdi-silverware-fork-knife @endsection


@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-md-12">
<div class="card py-3 m-b-30">

<div class="row">
    <div class="col-md-12" style="text-align: right;">
        <a href="{{ Asset($link.'add') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-warning">
        Agregar Nuevo
        </a>
        <a href="{{ Asset('import') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-success">
            Importar
        </a>
        <a href="{{ Asset('export') }}" class="btn m-b-15 ml-2 mr-2 btn-rounded btn-info">
            Exportar
        </a>&nbsp;&nbsp;&nbsp;
    </div>
</div>


<div class="card-body">

{{ Form::open(['route' => 'search_item', 'method' =>'GET','class' => 'col s12'])}}
    <div class="tab-content" id="myTabContent1">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        
            <div class="form-row">
            
            <div class="form-group col-md-3">
                <label for="inputEmail6">Nombre</label><br />
                <input type="text" name="name" id="name" value="{{$name}}" placeholder="Busca por nombre y/o Descripción" class="form-control" autocomplete="off">
            </div>
            
            <div class="form-group col-md-3">
                <label for="inputEmail6">Tipo Menu</label>
                <select name="cate" class="form-control" id="cate">
                    @foreach($category as $cat)
                        <option value="{{$cat->id}}" @if($cat->id == $cate) selected @endif>{{$cat->name}}</option>
                    @endforeach
                </select>
            </div>     
            <div class="form-group col-md-1">
                <button type="submit" class="btn btn-success m-b-15 ml-2 mr-2" style="margin-top:30px;">Buscar</button>
            </div>
            
            <div class="form-group col-md-2">
                <a href="{{ Asset($link) }}" class="btn btn-success m-b-15 ml-2 mr-2" style="margin-top:30px;">Ver todo</a>
            </div>
                
            </div>
        
        </div>
    </div>
{{Form::close()}}

<table class="table table-hover ">
<thead>
<tr>
<th>Imagen</th>
<th>Categoría</th>
<th>Nombre</th>
<th>DeepLink</th> 
<th>Estado</th>
<th style="text-align: right">Opciones</th>
</tr>

</thead>
<tbody>
    {{--  --}}
@foreach($data as $row)
<?php  
    $product_image = explode(",", $row->img);
    $product_image = htmlspecialchars($product_image[0]); 
?>
<tr> 
<td width="15%"> 
    @if($row->type_img == 0) 
    <img src="{{ Asset('upload/item/'.$product_image) }}" height="50"> 
    @else 
    <img src="{{$product_image}}" height="50"> 
    @endif
</td>
<td width="12%">{{ $row->cate }}</td>
<td width="17%">
    {{ $row->name }}
</td>
<td width="15%">
    <button class="btn m-b-15 ml-2 mr-2 btn-md  btn-success" onclick="ClipToDeepLink('{{  substr(md5($row->name),0,15) }}','item')">
       Copy Link
    </button>
</td>
<td width="12%">

@if($row->status == 0)
<button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
@else

<button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>

@endif

</td>

<td width="22%" style="text-align: right">
<!-- Complementos -->
<a href="javascript::void()" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-info" data-toggle="modal" data-target="#slideRightModal{{ $row->id }}"><i class="mdi mdi-link"></i></a>
<!-- Tranding -->
<a href="javascript::void()" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle <?php if($row->trending == 1){ echo "btn-success"; } else { echo "btn-warning"; } ?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php if($row->trending == 1){ echo "En Trending"; } else { echo "Marcar Trending"; } ?>" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id.'?type=trend') }}')"><i class="mdi mdi-trending-up"></i></a>
<!-- Editar -->
<a href="{{ Asset($link.$row->id.'/edit') }}" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-success" data-toggle="tooltip" data-placement="top" data-original-title="Edit This Entry"><i class="mdi mdi-border-color"></i></a>
<!-- Eliminar -->
<button type="button" class="btn m-b-15 ml-2 mr-2 btn-md  btn-rounded-circle btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Delete This Entry" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="mdi mdi-delete-forever"></i></button>


</td>
</tr>

@include('user.item.addon')

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