@extends('layouts.dashboard')

@section('title')
Équipes
@endsection

@section('smalltitle')
Liste des équipes
@endsection

@section('content')

@if (Authorization::can('ce','create'))
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="box-countdown">Fermeture dans {{ @countdown(Authorization::countdown('ce','create')) }}
                ou dans {{ $teamLeft }} créations d'équipes.</div>
            <h3 class="box-title">Créer mon équipe</h3>
        </div>
        <div class="box-body text-center">
            <p>
                Faites attention à prendre un nom d'équipe qui n'a pas déjà été pris en regardant la liste ci-dessous.<br/>
                Un seul membre de votre équipe doit créer l'équipe puis ajouter les autres.
                Le reste des membres de l'équipe devrons ensuite se connecter pour accepter d'être dans l'équipe.
            </p>
            <form class="" action="{{ route('dashboard.ce.teamcreate') }}" method="post">
                <input type="text" name="name" class="form-control text-center" placeholder="Nom de l'équipe" required>
                <input type="submit" class="btn btn-success form-control" value="Ajouter">
            </form>
        </div>
    </div>
@elseif (!Authorization::can('ce','inTeam'))
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Inscriptions des CE terminée</h3>
        </div>
        <div class="box-body">
            <p>
                Les inscriptions pour chef d'équipe ne sont pas ouvertes.
            </p>
        </div>
    </div>
@endif


<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des équipes</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <div class="box-body">
            <p>
                Si vous souhaitez rejoindre l'une de ces équipes, contactez le <em><i class="fa fa-star" aria-hidden="true" title="Responsable de l'équipe"></i> responsable de l'équipe</em> pour qu'il vous ajoute.
            </p>
        </div>
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Membres</th>
                </tr>
                @foreach ($teams as $team)
                    <tr>
                        <td>{{{ $team->name }}}</td>
                        <td>
                            @foreach ($team->ce as $ce)
                                @if ($ce->student_id == $team->respo_id)
                                    <i class="fa fa-star" aria-hidden="true" title="Responsable de l'équipe"></i>
                                @endif
                                @if ($ce->team_accepted)
                                    <i class="fa fa-check-circle" aria-hidden="true" title="A validé sa participation à l'équipe"></i>
                                @else
                                    <i class="fa fa-question-circle" aria-hidden="true" title="N'a pas encore validé sa participation à l'équipe"></i>
                                @endif

                                @if ($team->ce->last() == $ce)
                                    {{{ $ce->first_name }}} {{{ $ce->last_name }}}
                                @else
                                    {{{ $ce->first_name }}} {{{ $ce->last_name }}},
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
