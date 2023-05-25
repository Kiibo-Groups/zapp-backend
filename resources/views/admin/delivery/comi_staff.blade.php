<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
				<label for="type_driver">Tipo de vehiculo</label>
				<select name="type_driver" id="type_driver" class="form-control">
					<option value="0" @if($data->type_driver == 0) selected @endif>Auto</option>
					<option value="1" @if($data->type_driver == 1) selected @endif>Motocicleta</option> 
				</select>
            </div>
            <div class="form-group col-md-6">
                <label for="max_range_km">Rango máximo de entrega</label>
                <input type="text" name="max_range_km" id="max_range_km" value="{{$data->max_range_km}}" class="form-control">
            </div>

            <div class="form-group col-md-6">
				<label for="type_driver">Nivel de conductor</label>
				<select name="level_id" id="level_id" class="form-control">
					@foreach ($levels as $level)
                    <option value="{{$level->id}}" @if($data->level_id == $level->id) selected @endif>{{ $level->name }}</option>
                    @endforeach
				</select>
            </div>

            <div class="form-group col-md-6">
				<label for="orders_complets">Pedidos Realizados</label>
                <input type="number" name="orders_complets" id="orders_complets" value="{{$data->orders_complets}}" class="form-control">
            </div>
        </div>
    </div>
</div>

