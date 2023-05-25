
<input type="hidden" name="id" value="{{$data->id}}" />
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="inputEmail6">selecciona una categoría <small>(Debes Agregarlas en area de categorias)</small></label>
			<select name="cate_id" class="form-control" required="required">
			<option value="">Select</option>
			@foreach($category as $cat)
				<option value="{{$cat->id}}" @if($data->category_id == $cat->id) selected @endif>{{$cat->name}}</option>
			@endforeach
			</select>
		</div>

		<div class="form-group col-md-6">
			<label for="inputEmail6">Nombre</label>
			{!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control'])!!}
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="inputEmail6">Estado</label>
			<select name="status" class="form-control">
				<option value="0" @if($data->status == 0) selected @endif>Active</option>
				<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="sort">Orden de clasificación</label>
			{!! Form::number('sort_no',null,['id' => 'sort','class' => 'form-control'])!!}
		</div>
		<div class="form-group col-md-3">
			<label for="qty">Cantidad</label>
			{!! Form::number('qty',null,['id' => 'qty','class' => 'form-control'])!!}
		</div> 
	</div>
	
	<div class="form-row">
		<div class="form-group col-md-4">
			<label for="inputEmail6">Precio</label>
			{!! Form::text('small_price',null,['id' => 'code','placeholder' => 'Precio final al consumidor','class' => 'form-control'])!!}
		</div>

		<div class="form-group col-md-4">
			<label for="last_price">Precio antes del descuento </label>
			{!! Form::text('last_price',null,['id' => 'last_price','placeholder' => 'Precio antes del descuento','class' => 'form-control'])!!}
		</div>
	
		<div class="form-group col-md-4">
			<label for="inputEmail6">Agregar Conjunto de Modificadores (Extras)</label>
			@csrf
			<input type="hidden" name="item_id" value="{{ $data->id }}">


			<select name="a_id[]" class="form-control js-select2" multiple="true">
			@foreach($cates as $cate)
				@if($cate->type == 1)
				<option value="{{ $cate->id }}" @if(in_array($cate->id,$arrayCate)) selected @endif>
					{{ $cate->name }}
					@if($cate->id_element != '')
						<small>({{$cate->id_element}})</small>
					@endif
				</option>
				@endif
			@endforeach
			</select>
		</div> 
	</div>

	<div class="form-row">
		<div class="form-group col-md-12">
			<label for="description">Descripción</label>
			<textarea name="description" id="description" cols="30" rows="10" placeholder="Descripción" class="form-control">{{$data->description}}</textarea>
		</div>
	</div>

	<div class="form-row">
		
		 @if($data->id)
			<div class="col-md-6">
				<div class="form-group">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Imagen</th>
								<th>Opcion</th>
							</tr>
						</thead>
						<tbody>
							<?php $images = explode(",", $data->img)?>
							@foreach($images as $key => $image)
							<tr>
								<td>
									@if($data->type_img == 1)
									<img src="{{$image}}" height="60" width="60">
									@else 
									<img src="{{url('upload/item/', $image)}}" height="60" width="60">
									@endif
									<input type="hidden" name="prev_img[]" value="{{$image}}">
								</td>
								<td><button type="button" class="btn btn-sm btn-danger remove-img">X</button></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endif

		<div class="form-group @if(!$data->id) col-md-12 @else col-md-6 @endif">
			<label for="inputEmail6">Imagenes</label>
			{{-- <input type="file" name="img[]" class="form-control" multiple @if(!$data->id) required="required" @endif> --}}
			 
			<div id="imageUpload" class="dropzone"></div>
			<span class="validation-msg" id="image-error"></span>
		</div>
	</div>

</div>
<button type="submit" id="submit-btn" class="btn btn-success btn-cta">Guardar Cambios</button>



@section('js')

<script> 
	$(".remove-img").on("click", function () {
        $(this).closest("tr").remove();
    });

	$(".dropzone").sortable({
        items:'.dz-preview',
        cursor: 'grab',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        tolerance: 'pointer',
        stop: function () {
          var queue = myDropzone.getAcceptedFiles();
          newQueue = [];
          $('#imageUpload .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {           
                var name = el.innerHTML;
                queue.forEach(function(file) {
                    if (file.name === name) {
                        newQueue.push(file);
                    }
                });
          });
          myDropzone.files = newQueue;
        }
    });

	let url = "{{ (!$data->id) ? env('user').'/item' : env('user').'/item/update' }}";  
	let method = "{{ (!$data->id) ? 'POST' : 'POST' }}";  
	
	console.log(url);
    myDropzone = new Dropzone('div#imageUpload', {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFilesize: 12,
        paramName: 'img',
        clickable: true,
        method: method,
        url: url, 
		headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function () {
            var myDropzone = this; 
			$('#submit-btn').on("click", function (e) {
                e.preventDefault();
                if(myDropzone.getAcceptedFiles().length) {
                        myDropzone.processQueue();
				}else { 
					$.ajax({
						type:method,
						url:url, 
						data: $("#product-form").serialize(), 
						success:function(response){
							console.log(response);
							location.href = "{{env('user').'/item'}}";
						},
						error:function(response) { 

							console.log(response);
							if(response.responseJSON.message) {
								$("#name-error").text(response.responseJSON.message);
							}
							else if(response.responseJSON.status) {
								$("#code-error").text(response.responseJSON.status);
							}
						},
					});
				}
            });

            this.on('sending', function (file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $("#product-form").serializeArray();
				console.log(data);
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });
        },
		sending: function(file, xhr, formData) {
			// Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
			formData.append("_token", $('[name=_token').val()); // Laravel expect the token post value to be named _token by default
		},
        error: function (file, response) {
            console.log("error => ",response);
			console.log(file);
			console.log(method, url);
			// if(response.message) {
			// 	$("#name-error").text(response.message);
			// }
			// else if(response.status) {
			// 	$("#code-error").text(response.status);
			// }
        },
        successmultiple: function (file, response) {
            location.href = "{{env('user').'/item'}}";
            console.log(file, response);
        },
        completemultiple: function (file, response) {
            // console.log(file, response, "completemultiple");
        },
        reset: function () {
            console.log("resetFiles");
            this.removeAllFiles(true);
        }
    });

</script>

@endsection