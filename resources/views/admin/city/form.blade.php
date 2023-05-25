<div class="card py-3 m-b-30">
	<div class="card-body">
		<div class="form-row">
			@include('admin.city.google')
		</div>
	</div>
</div>

<h1 style="font-size: 20px">Establecer Distancia de servicio</h1>
<div class="card py-3 m-b-30">
	<div class="card-body"> 
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="max_distance">Distancia Maxima de servicio.</label>
				<input type="text" name="max_distance" id="max_distance" class="form-control"  required="required" min="0" value="{{$data->max_distance}}">
			</div>

			<div class="form-group col-md-6">
				<label for="inputEmail6">Status</label>
				<select name="status" class="form-control">
					<option value="0" @if($data->status == 0) selected @endif>Active</option>
					<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
				</select>
			</div>
		</div>
	</div>
</div>

<h1 style="font-size: 20px">Establecer cargos de comisión de envio para Motocicleta</h1>
<div class="card py-3 m-b-30">
	<!-- Motocicleta -->
	<div class="card-body"> 
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputEmail6">Tipo  de Comision</label>
			
				<select name="c_type" class="form-control">
				<option value="0" @if($data->c_type == 0) selected @endif>Valor por KM</option>
				<option value="1" @if($data->c_type == 1) selected @endif>Valor fijo</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="inputEmail6">Valor de la comisión</label>
				<input type="text" name="c_value" value="{{$data->c_value}}" class="form-control">
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="min_distance">Distancia minima de Servicio <small>(Distancia en KM de 0 a )</small> </label>
				<input type="text" name="min_distance" value="{{$data->min_distance}}" class="form-control">
			</div>

			<div class="form-group col-md-6">
				<label for="min_value">Cobro por el Minimo de servicio <small>(Valor Fijo en $)</small> </label>
				<input type="text" name="min_value" value="{{$data->min_value}}" class="form-control">
			</div>
			
		</div>

	</div> 
</div>

<h1 style="font-size: 20px">Establecer cargos de comisión de envio para Autos</h1>
<div class="card py-3 m-b-30">
	<!-- Motocicleta -->
	<div class="card-body"> 
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputEmail6">Tipo  de Comision</label>
			
				<select name="c_type_aut" class="form-control">
				<option value="0" @if($data->c_type_aut == 0) selected @endif>Valor por KM</option>
				<option value="1" @if($data->c_type_aut == 1) selected @endif>Valor fijo</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="inputEmail6">Valor de la comisión</label>
				<input type="text" name="c_value_aut" value="{{$data->c_value_aut}}" class="form-control">
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="min_distance">Distancia minima de Servicio <small>(Distancia en KM de 0 a )</small> </label>
				<input type="text" name="min_distance_aut" value="{{$data->min_distance_aut}}" class="form-control">
			</div>

			<div class="form-group col-md-6">
				<label for="min_value">Cobro por el Minimo de servicio <small>(Valor Fijo en $)</small> </label>
				<input type="text" name="min_value_aut" value="{{$data->min_value_aut}}" class="form-control">
			</div>
			
		</div>

	</div> 
</div>

<h1 style="font-size: 20px">Cargos de comisión para Socios Repartidores</h1>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="form-row">
			<div class="form-group col-md-5">
				<label for="type_driver">Tipo de vehiculo</label>
				<select name="type_driver" id="type_driver" class="form-control">
					<option value="0" @if($data->type_driver == 0) selected @endif>Auto</option> 
				</select>
            </div>

            <div class="form-group col-md-4">
                <label for="c_type_staff">Tipo de comisión</label>
                <select name="c_type_staff" class="form-control"> 
                    <option value="1" @if($data->c_type_staff == 1) selected @endif>valor en %</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="c_value_staff">Valor de la comisión</label>
                <input type="text" name="c_value_staff" id="c_value_staff" value="{{$data->c_value_staff}}" class="form-control">
            </div>
        </div>
		<div class="form-row">
			<div class="form-group col-md-5">
				<label for="type_driver">Tipo de vehiculo</label>
				<select name="type_driver" id="type_driver" class="form-control"> 
					<option value="1" @if($data->type_driver == 1) selected @endif>Motocicleta</option> 
				</select>
            </div>

            <div class="form-group col-md-4">
                <label for="c_type_staff2">Tipo de comisión</label>
                <select name="c_type_staff2" id="c_type_staff2" class="form-control"> 
                    <option value="1" @if($data->c_type_staff2 == 1) selected @endif>valor en %</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="c_value_staff2">Valor de la comisión</label>
                <input type="text" name="c_value_staff2" id="c_value_staff2" value="{{$data->c_value_staff2}}" class="form-control">
            </div>
        </div>
    </div>
</div>

<div class="form-row m-b-30">
	<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
</div>
