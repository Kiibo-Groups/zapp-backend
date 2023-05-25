@extends('admin.layout.main')

@section('title') Add New @endsection

@section('icon') mdi-map-marker @endsection


@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                {!! Form::model($data, ['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
                    @include('admin.city.form') 
                </form>
            </div>
        </div>
    </div>
</section>

@endsection