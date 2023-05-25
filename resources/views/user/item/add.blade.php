@extends('user.layout.main')

@section('title') Agregar nuevo @endsection

@section('icon') mdi-silverware-fork-knife @endsection


@section('content')

<section class="pull-up">
<div class="container">
<div class="row ">
<div class="col-lg-12 mx-auto  mt-2">
<div class="card py-3 m-b-30">
<div class="card-body"> 
    <form id="product-form">
        @include('user.item.form')
    </form>
</div>
</div>
</div>
</div>
</div>
</section>

@endsection