@extends('layouts.master')

@section('css')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('bodycontent')
    <div class="skin-blue layout-top-nav">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <i class="fa fa-bars"></i>
                            </button>
                            <a href="{{ route('dashboard.index') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">


                                @if (EtuUTT::student()->isAdmin())
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Parrainage <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.referrals.list') }}">Liste des parrains</a></li>
                                            <li><a href="{{ route('dashboard.referrals.validation') }}">Validation</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('dashboard.students.list') }}">Etudiants</a></li>
                                    <?php /* <li><a href="{{ route('dashboard.newcomers') }}">Nouveaux</a></li> -->
                                    <!-- <li><a href="{{ route('dashboard.teams') }}">Équipes</a></li> -->
                                    <!-- <li><a href="{{ route('dashboard.exports') }}">Export</a></li> -->
                                    <!-- <li><a href="{{ route('dashboard.championship') }}">Factions</a></li> -->
                                    <!-- <li><a href="{{ route('dashboard.wei') }}">WEI</a></li> --> */ ?>
                                @endif
                                @if (EtuUTT::student()->ce)
                                    @if (!EtuUTT::student()->team()->count())
                                        <li><a href="{{ route('dashboard.ce.teamlist') }}">Créer une équipe</a></li>
                                    @else
                                        <li><a href="{{ route('dashboard.ce.myteam') }}">Mon équipe</a></li>
                                        <li><a href="{{ route('dashboard.ce.teamlist') }}">Liste des équipes</a></li>
                                    @endif
                                @endif
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mon compte <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('dashboard.students.profil') }}"><i class="fa fa-user" aria-hidden="true"></i> Mon profil bénévole</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ route('menu') }}"><i class="fa fa-bars" aria-hidden="true"></i> Menu princpal</a></li>
                                        <li><a href="{{ route('index') }}"><i class="fa fa-home" aria-hidden="true"></i> Page d'accueil</a></li>
                                        <li><a href="{{ route('oauth.logout') }}"><i class="fa fa-power-off" aria-hidden="true"></i> Se déconnecter</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
                        <h1>
                            @yield('title')
                            <small>@yield('smalltitle')</small>
                        </h1>
                    </section>
                    <section class="content">
                        @if ( Session::has('flash_message') )
                            <div class="alert alert-{{ empty(Session::get('flash_type'))?'success':Session::get('flash_type') }}">
                                {{ Session::get('flash_message') }}
                            </div>
                        @endif
                        @yield('content')
                    </section>
                </div>
            </div>

            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> {{ Config::get('services.version.hash')}}
                    </div>
                    <strong>En cas de problème,</strong> contacter <a href="mailto:aurelien.labate@utt.fr">Alabate</a> (pas trop non plus hein) (non mais c'est censé marcher) (t'as rebooté ?).
                </div>
            </footer>
        </div>
    </div>
@endsection
