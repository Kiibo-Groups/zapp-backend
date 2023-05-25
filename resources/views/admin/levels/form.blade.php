
<div class="form-row">
    <input type="text" name="deliveryVia" value="admin" hidden>
    
    <div class="form-group col-md-6">
        <label for="name">Nombre</label>
        {!! Form::text('name',null,['id' => 'name','class' => 'form-control','required' => 'required'])!!}
    </div>
    <div class="form-group col-md-6">
        <label for="nivel">Cantidad de servicios requeridos</label>
        {!! Form::number('nivel',null,['id' => 'nivel','class' => 'form-control','required' => 'required'])!!}
    </div>
    <div class="form-group col-md-6">
        <label for="max_cash">Valor maximo del pedido</label>
        {!! Form::number('max_cash',null,['id' => 'max_cash','class' => 'form-control','required' => 'required'])!!}
    </div>

    <div class="form-group col-md-12">
        <label for="descript">Descripción</label>
        <textarea name="descript" id="descript" cols="30" rows="10" class="form-control">{{$data->descript}}</textarea>
    </div>
</div> 
    