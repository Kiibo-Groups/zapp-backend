@if($data->id)
<img src="{{ Asset('upload/banner/'.$data->img) }}" height="100"><br><br>
@endif

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="inputEmail6">Ciudad en que se mostrara</label>
        <select name="city_id" class="form-control">
            <option value="">Todas las ciudades</option>
            @foreach($citys as $city)
            <option value="{{ $city->id }}" @if($data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="inputEmail6">Posición del banner</label>
        <select name="position" class="form-control" required="required">
            <option value="0" @if($data->position == 0) selected @endif>Principal (270px * 140px)</option>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label for="inputEmail6">Imagen</label>
        <input type="file" name="img" class="form-control" @if(!$data->id) required="required" @endif>
    </div>
</div>

<div class="form-row">

    <div class="form-group col-md-6">
        <label for="type_linkin">Selecciona el tipo de redirección</label>
        <select name="design_type"  class="form-control js-select2" id="type_linkin" onchange="changeTypeLink()">
            <option value="0" @if($data->design_type == 0) selected @endif>Para negocio</option>
            <option value="1" @if($data->design_type == 1) selected @endif>Para producto</option>
        </select>
    </div>
    
    <div class="form-group col-md-6">
        <label for="inputEmail6">Status</label>
        <select name="status" class="form-control" required="required">
            <option value="0" @if($data->status == 0) selected @endif>Activo</option>
            <option value="1" @if($data->status == 1) selected @endif>No Disponible</option>
        </select>
    </div>
</div>

<div class="form-row">

    <div class="form-group col-md-12" id="type-store">
        <label for="inputEmail4">Negocios <small>(Aparecerán en la lista al hacer clic en el banner)</small></label>
        <select name="store[]" class="form-control js-select2">
            <option value="">Sin enlace</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @if(isset($array) && in_array($user->id,$array)) selected @endif>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-12" id="type-item" style="display: none;">
        <label for="inputEmail4">Productos <small>(Aparecerán en la lista al hacer clic en el banner)</small></label>
        <select name="item[]" class="form-control js-select2">
            <option value="">Sin enlace</option>
            @foreach($items as $item)
            <option value="{{ $item->id }}" @if(isset($array_it) && in_array($item->id,$array_it)) selected @endif>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    
</div>

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>


@section('js')
<script>
    let type = document.getElementById("type_linkin").value;
    console.log("Type ->" , type);

    changeTypeLink(); // Inizializamos

    function changeTypeLink()
    {
        let type = document.getElementById("type_linkin").value;
        console.log("Type ->" , type);
        storeWrap = document.getElementById('type-store');
        itemWrap  = document.getElementById('type-item');
        
        if (type == 0) { // Store
            itemWrap.style.display = "none";
            storeWrap.style.display = "block";        
        }else {
            itemWrap.style.display = "block";
            storeWrap.style.display = "none";
        }

        $(document).ready(function() {
            $('.js-select2').select2();
        });
    }
</script>
@endsection
