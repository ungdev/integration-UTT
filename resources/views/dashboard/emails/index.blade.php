@extends('layouts.dashboard')

@section('title')
Emails
@endsection

@section('smalltitle')
Envoi d'emails en maaasse
@endsection

@section('content')

<div class="callout callout-info">
    <h4>Informations</h4>
    <p>
        Une fois programmé les emails seront envoyés un par un, séparés de 5 secondes. Donc si vous envoyez à 600 personnes prévoyez un peu plus de 1h d'envoi. Evitez de chevaucher les envois d'emails.
    </p>
    <p>
        La fonction email étant en developpement, il faut modifier et programmer les emails directement depuis la base de donnée. Il n'y a pas d'interface pour le faire.
    </p>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des envoi d'emails</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Contenu</th>
                    <th>Destinataires</th>
                    <th>Progammé pour</th>
                    <th>Etat</th>
                    <th>Action</th>
                </tr>
                @foreach ($emails as $email)
                    <tr>
                        <td>
                            <a href="#email{{$email->id}}" data-toggle="collapse">{{$email->subject}}</a>
                            <pre id="email{{$email->id}}" class="collapse">{{$email->template}}</pre>
                        </td>
                        <td>
                            @if($email->donelist)
                                <a href="#emailList{{$email->id}}" data-toggle="collapse">{{$email::$listToFrench[$email->list]}}</a>
                                <div id="emailList{{$email->id}}" class="collapse">
                                    <strong>Emails envoyés :</strong>
                                    <pre>{{$email->donelist}}</pre>
                                </div>
                            @else
                                {!! $email::$listToFrench[$email->list] !!}
                            @endif
                        </td>
                        <td>
                            @if($email->scheduled_for)
                                {{(new Datetime($email->scheduled_for))->format('H\h \l\e d/m/Y')}}
                            @endif
                        </td>
                        <td>
                            @if($email->started && $email->total != 0 && $email->done == $email->total)
                                <span class="label label-success">Terminé ({{$email->done}})</span>
                            @elseif($email->started && $email->total != 0)
                                <span class="label label-warning">En cours ({{$email->done}}/{{$email->total}})</span>
                            @elseif($email->started)
                                <span class="label label-warning">En cours</span>
                            @elseif($email->scheduled_for && !$email->started)
                                <span class="label label-info">Progammé</span>
                            @else
                                <span class="label label-danger">Non programmé</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info" href="{{ route('dashboard.emails.preview', ['id'=>$email->id])}}">Prévisualiser</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection