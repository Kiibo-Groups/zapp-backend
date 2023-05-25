<div class="modal fade modal-slide-right" id="slideRightModal{{ $row->id }}" tabindex="-1" role="dialog aria-labelledby="slideRightModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="slideRightModalLabel">Asignar complementos a {{ $row->name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <form action="{{ Asset('itemAddon/') }}" method="post">
        <div class="modal-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="item_id" value="{{ $row->id }}">
            <div class="row">
                @foreach($addon as $a)
                <div class="col-md-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck{{ $a->id.$row->id }}" value="{{ $a->id }}" name="a_idAdd[]" @if(in_array($a->id,$assign->getAssigned($row->id))) checked @endif>
                        <label class="custom-control-label" for="customCheck{{ $a->id.$row->id }}">{{ $a->name }} <br /><small>Categoria: ({{ $a->cate }})</small> </label>
                    </div>
                    <br>
                </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>    
        </div>
    </form>
</div>
</div>
</div>


</div>