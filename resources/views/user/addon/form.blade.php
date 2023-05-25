<div class="tab-content" id="myTabContent1">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail6">selecciona una categoría <small>(Debes Agregarlas en area de <a href="{{ Asset(env('user').'/category/add') }}" style="color:blue;">categorias</a> )</small></label>
            <select name="cate_id" class="form-control" required="required">
            <option value="">Select</option>
            @foreach($cates as $cate)
                @if($cate->type == 1)
                <option value="{{ $cate->id }}" @if($data->category_id == $cate->id) selected @endif>{{ $cate->name }}
                @if($cate->id_element != '')
                    <small>({{$cate->id_element}})</small>
                    @endif</option>
                @endif
            @endforeach
            </select>
        </div>
            <div class="form-group col-md-6">
                <label for="inputEmail6">Nombre</label>
                {!! Form::text('name',null,['id' => 'code','placeholder' => 'Name','class' => 'form-control','required' => 'required'])!!}
            </div>

            <div class="form-group col-md-6">
                <label for="inputEmail6">Precio</label>
                {!! Form::text('price',null,['id' => 'code','class' => 'form-control','required' => 'required'])!!}
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-success btn-cta">Save changes</button>
