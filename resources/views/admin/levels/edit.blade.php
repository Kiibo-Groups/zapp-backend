@extends('admin.layout.main')

@section('title') Editar Nivel @endsection

@section('content')

<section class="pull-up">
    <div class="container">
        <div class="row ">
            <div class="col-lg-10 mx-auto  mt-2">
                {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        @include('admin.levels.form')
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
