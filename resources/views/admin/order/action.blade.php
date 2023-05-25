@include('admin.order.dispatch')
@include('admin.order.info_pay')
@include('admin.order.assignStaff')

@if($row->status == 0)
    <div style="text-align: right">
        <button class="btn btn-primary dropdown-toggle" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Opciones
        </button>
        
        <ul class="dropdown-menu"  style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
            <li>
                <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=1') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                    Confirmar Pedido
                </a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/order/print/'.$row->id) }}" target="_blank" class="dropdown-item">
                    Imprimir Recibo
                </a>
            </li>
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalInfoPay{{ $row->id }}" class="dropdown-item">
                    Desglose de información
                </a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                    Cancelar Pedido
                </a>
            </li>
            
        </ul>
    </div>
@elseif($row->status == 1)
    <div style="text-align: right">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Opciones
        </button>
        
        <ul class="dropdown-menu"  style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
            @if($row->type == 1)
                <li>
                    <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalStaff{{ $row->id }}" class="dropdown-item">
                        Solicitar Repartidor
                    </a>
                </li>
                <li>
                    <a href="javascript::void()" data-toggle="modal" data-target="#assignModalStaff{{ $row->id }}" class="dropdown-item">
                        Asignar Repartidor Unico
                    </a>
                </li>
            @elseif($row->type == 2)
                <li>
                    <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=7') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                        Listo para entregar
                    </a>
                </li>
            @elseif($row->type == 3)
                <li>
                    <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=7') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                        Listo para servir
                    </a>
                </li>
            @elseif($row->type == 7)
                <li>
                    <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=5') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                        Entregar Pedido
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ Asset(env('admin').'/order/print/'.$row->id) }}" target="_blank" class="dropdown-item">
                    Imprimir Recibo
                </a>
            </li>
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalInfoPay{{ $row->id }}" class="dropdown-item">
                    Desglose de información
                </a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                    Cancelar Pedido
                </a>
            </li>
            
        </ul>
    </div>
@elseif($row->status == 1.5 || $row->status == 3 || $row->status == 4)
    <div style="text-align: right">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Opciones
        </button>
        
        <ul class="dropdown-menu"  style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalStaff{{ $row->id }}" class="dropdown-item">
                    Solicitar Repartidor
                </a>
            </li>
            @if($row->status == 3 || $row->status == 4)
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#assignModalStaff{{ $row->id }}" class="dropdown-item">
                    Asignar Repartidor Unico
                </a>
            </li>
            @endif
            <li>
                <a href="{{ Asset(env('admin').'/order/print/'.$row->id) }}" target="_blank" class="dropdown-item">
                    Imprimir Recibo
                </a>
            </li>
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalInfoPay{{ $row->id }}" class="dropdown-item">
                    Desglose de información
                </a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/orderStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Are you sure?')" class="dropdown-item">
                    Cancelar Pedido
                </a>
            </li>
            
        </ul>
    </div>
@elseif($row->status == 2)
    <div style="text-align: right">
        <button href="javascript::void()" data-toggle="modal" data-target="#slideRightModalInfoPay{{ $row->id }}" class="btn btn-secondary">
            Desglose de información
        </button>
    </div>
@elseif($row->status == 5 || $row->status == 6)
     

    <div style="text-align: right">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Opciones
        </button>
        
        <ul class="dropdown-menu"  style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
            <li>
                <a href="javascript::void()" data-toggle="modal" data-target="#slideRightModalInfoPay{{ $row->id }}" class="dropdown-item">
                    Desglose de información
                </a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/order/print/'.$row->id) }}" target="_blank" class="dropdown-item">
                    Imprimir Recibo
                </a>
            </li>
            <li>
                <a href="{{ Asset('upload/order/delivery/delivery_end_'.$row->id.'.jpg') }}" target="_blank"  class="dropdown-item">
                    Ver Imagen de entrega
                </a>
            </li>
            
        </ul>
    </div>
@endif