@extends('admin.layout.main')

@section('title') Información de su cuenta @endsection

@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 mx-auto mt-2">
                <div class="tab-content" id="myTabContent1">
                    <form action="{{ $form_url }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <div class="card py-3 m-b-30">
                                <div class="card-body">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail6">Name</label>
                                            <input type="text" value="{{ $data->name }}" class="form-control" id="inputEmail6" name="name" required="required">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Email</label>
                                            <input type="email" class="form-control" id="inputEmail4" name="email" value="{{ $data->email }}" required="required">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Username</label>
                                            <input type="text" class="form-control" id="asd" name="username" value="{{ $data->username }}" required="required">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="asd">Logo</label>
                                            <input type="file" class="form-control" id="asd" name="logo">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Currency <small>(e.g $, &pound; &#8377;)</small></label>
                                            <input type="text" class="form-control" id="asd" name="currency" value="{{ $data->currency }}" required="required">
                                        </div>
                                        <div class="form-group col-md-6">
                                            @if($data->logo)
                                            <img src="{{ Asset('upload/admin/'.$data->logo) }}" width="50" >
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> 
 
                            <h1 style="font-size: 20px">Establecer cargos de comisión por servicio de mandaditos<br />
                            <small style="font-size:12px;">(dejar en 0 si no requiere cobrar comisión)</small></h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="t_type_comm">Tipo de Comision</label>
                                            <select name="t_type_comm" id="t_type_comm" class="form-control">
                                            <option value="0" @if($data->t_type_comm == 0) selected @endif>Valor fijo</option>
                                            <option value="1" @if($data->t_type_comm == 1) selected @endif>Order %</option>
                                            </select>
                                        </div>
    
                                        <div class="form-group col-md-6">
                                            <label for="t_value_comm">Valor de la comisión</label>
                                            <input type="text" name="t_value_comm" id="t_value_comm" value="{{$data->t_value_comm}}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="shipping_insurance">% sobre el valor declarado <small>(Seguro de envio)</small></label>
                                            <input type="text" name="shipping_insurance" id="shipping_insurance" value="{{$data->shipping_insurance}}" class="form-control">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="max_insurance">Valor maximo para el valor declarado</label>
                                            <input type="text" name="max_insurance" id="max_insurance" value="{{$data->max_insurance}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h1 style="font-size: 20px">Establecer valor máximo para pago en efectivo</h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail6">Valor máximo</label>
                                            <input type="text" name="max_cash" value="{{$data->max_cash}}" class="form-control">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <h1 style="font-size: 20px">Establecer distancia maxima para notificación de repartidores.</h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="max_distance_staff">Distancia Maxima</label>
                                            <input type="text" name="max_distance_staff" value="{{$data->max_distance_staff}}" class="form-control" id="max_distance_staff">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <h1 style="font-size: 20px">Establecer distancia minima para entrega de pedido.</h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="max_distance_staff_acpt">Distancia Minima</label>
                                            <input type="text" name="max_distance_staff_acpt" value="{{$data->max_distance_staff_acpt}}" class="form-control" id="max_distance_staff">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            {{-- <h1 style="font-size: 20px">Establecer cargos de comisión por pago con tarjeta</h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail6">Terminal a domicilio</label>
                                        
                                            <select name="send_terminal" class="form-control">
                                            <option value="0" @if($data->send_terminal == 0) selected @endif>No Brindar Servicio</option>
                                            <option value="1" @if($data->send_terminal == 1) selected @endif>Brindar Servicio</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="comm_stripe">Valor de la comisión <small>(% que se cobrara)</small> </label>
                                            <input type="text" name="comm_stripe" value="{{$data->comm_stripe}}" class="form-control">
                                        </div> 
                                    </div>
                                </div>
                            </div> --}}

                            <h4>Google ApiKey <br /><small style="font-size: 12px">(Introduce el ApiKey de tu cuenta en <a href="https://cloud.google.com/" target="_blank">https://cloud.google.com/</a> )</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="ApiKey_google">ApiKey</label>
                                            <input type="text" class="form-control" id="ApiKey_google" name="ApiKey_google" value="{{ $data->ApiKey_google }}">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <h4>Google Tracker ID <br /><small style="font-size: 12px">(Introduce el TrackerID de tu cuenta en <a href="https://cloud.google.com/" target="_blank">https://cloud.google.com/</a> )</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="google_tacker_id">Tracker ID</label>
                                            <input type="text" class="form-control" id="google_tacker_id" name="google_tacker_id" value="{{ $data->google_tacker_id }}">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <h4>App Versión <br /><small style="font-size: 12px">(Introduce la Versión de las app's en <a href="https://play.google.com/store/apps/developer?id=Zapp+Logistica+SAS" target="_blank">PlayStore - Zapp Store</a> )</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="app_version">App Versión Usuarios</label>
                                            <input type="text" class="form-control" id="app_version" name="app_version" value="{{ $data->app_version }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="app_version_staff">App Versión repartidores</label>
                                            <input type="text" class="form-control" id="app_version_staff" name="app_version_staff" value="{{ $data->app_version_staff }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>App Versión IOS<br /><small style="font-size: 12px">(Introduce la Versión de las app's en <a href="https://apps.apple.com/br/app/zapp-logistica/id1610368026" target="_blank">AppStore - Zapp Store</a> )</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="app_version_ios">App Versión Usuarios</label>
                                            <input type="text" class="form-control" id="app_version_ios" name="app_version_ios" value="{{ $data->app_version_ios }}">
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <h4>Social Links</h4>
                            <div class="card py-3 m-b-30">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="asd">Facebook</label>
                                        <input type="text" class="form-control" id="asd" name="fb" value="{{ $data->fb }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="asd">Instagram</label>
                                        <input type="text" class="form-control" id="asd" name="insta" value="{{ $data->insta }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="asd">Twitter</label>
                                        <input type="text" class="form-control" id="asd" name="twitter" value="{{ $data->twitter }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="asd">Youtube</label>
                                        <input type="text" class="form-control" id="asd" name="youtube" value="{{ $data->youtube }}">
                                    </div>
                                </div>
                            </div>
                            </div>

                            <h4>Change Password</h4>
                            <div class="card py-3 m-b-30">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Current Password</label>
                                        <input type="password" class="form-control" id="inputPassword4" name="password" required="required" placeholder="Enter Your Current Password For Save Setting">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">New Password <small style="color:red">(if u want to change current password)</small></label>
                                        <input type="password" class="form-control" id="inputPassword4" name="new_password">
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection