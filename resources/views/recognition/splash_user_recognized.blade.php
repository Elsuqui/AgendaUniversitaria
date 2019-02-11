@extends("layouts.app")
@section('css')

@endsection

@section('content')
    <div class="ui container" style="padding-top: 10%;">
        <div class="ui equal width center aligned padded grid stackable">
            <div class="row">
                <div class="eight wide column">
                    <div class="ui raised segments">
                        <div class="ui top attached compact segment inverted nightli">
                            <h3 class="ui header">
                                Sistema de Planificacion y Agendamiento Docente UCSG
                            </h3>
                        </div>
                        <div class="ui compact attached segment" id="contendor">
                            <h4>Bienvenido {{ Auth::getName() }}</h4>
                            <img class="ui small circular image"
                                 src="{{ Auth::getUser()->getAvatarImagePath() == "" ? asset("imagenes/default-user.png") : Auth::getUser()->getAvatarImagePath() }}">
                            <div class="ui active centered inline loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection


