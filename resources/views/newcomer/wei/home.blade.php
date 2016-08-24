@extends('layouts.newcomer')

@section('css')
		<link rel="stylesheet" href="{{ @asset('css/flipclock.css') }}">
@endsection

@section('js')
    <script src="{{ @asset('js/flipclock.min.js') }}"></script>
    <script>
    var countdown = $('.countdown').FlipClock({{ (new DateTime(Config::get('services.wei.registrationStart')))->getTimestamp() - (new DateTime())->getTimestamp() }}, {
        countdown: true,
		clockFace: 'DailyCounter',
		language: 'french',
    });
    </script>
@endsection

@section('title')
Inscription au WEI
@endsection

@section('smalltitle')
Le Week-End d'Intégration
@endsection

@section('content')
    @if(Authorization::can('newcomer','wei'))

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Mon inscription au Week-End</h3>
			</div>
			<div class="box-body">
				<p>Le week-end d’intégration commence le vendredi 9 septembre à 11h30 par un voyage en bus dans un lieu dont on garde le mystère (c’est pas Dunkerque promis !). Durant ce week-end, de nombreuses activités, soirées et surprises te seront proposés, c’est surtout l’occasion de rencontrer pleins de nouveaux, des futurs potes ;-). On te ramène à Troyes, le dimanche au soir vers 18h.</p>
				<p>Le prix du week-end est de 55€ , on te demandera également une caution de 60€.</p>


			@if(!Auth::user()->isPageChecked('profil'))
	            <div class="text-center">
	                <big>Tu dois compléter totalement ton profil pour pouvoir t'inscrire au week-end !</big><br/>
					<a href="{{route('newcomer.profil')}}" class="btn btn-primary">Compléter mon profil</a>
            	</div>
			@elseif(!Auth::user()->wei && !Auth::user()->parent_authorization)
				<a href="{{route('newcomer.wei.pay')}}" class="btn btn-primary">S'inscrire au week-end</a><br/>
			@else

				@if($wei)
					<big>
						<i class="fa fa-check-square-o" aria-hidden="true"></i>
						Payer le week-end
					</big><br/>
				@else
					<big><a href="{{ route('newcomer.wei.pay') }}">
						<i class="fa fa-square-o" aria-hidden="true"></i>
						Payer le week-end
					</a></big><br/>
				@endif

				@if($sandwich)
					<big>
						<i class="fa fa-check-square-o" aria-hidden="true"></i>
						Prendre le panier repas du vendredi midi
					</big><br/>
				@else
					<big><a href="{{ route('newcomer.wei.pay') }}">
						<i class="fa fa-square-o" aria-hidden="true"></i>
						Prendre le panier repas du vendredi midi
					</a></big><br/>
				@endif

				@if($guarantee)
					<big>
						<i class="fa fa-check-square-o" aria-hidden="true"></i>
						Déposer la caution
					</big><br/>
				@else
					<big><a href="{{ route('newcomer.wei.guarantee') }}">
						<i class="fa fa-square-o" aria-hidden="true"></i>
						Déposer la caution
					</a></big><br/>
				@endif

				@if($underage)
					@if($authorization)
						<big>
							<i class="fa fa-check-square-o" aria-hidden="true"></i>
							Préparer l'autorisation parentale
						</big><br/>
					@else
						<big><a href="{{ route('newcomer.wei.authorization') }}">
							<i class="fa fa-square-o" aria-hidden="true"></i>
							Préparer l'autorisation parentale
						</a></big><br/>
					@endif
				@endif

				<small>Note : Pour les opérations manuelles (donner l'autorisation parentale, payer par chèque...), la case sera coché une fois que l'action aura été fait grâce au stand qui sera installé pendant la semaine d'intégration à l'UTT.</small>
            </div>
			@endif
        </div>
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">A ne pas oublier pour ton Week-End !</h3>
			</div>
			<div class="box-body">
				<ul>
					<li>Un duvet</li>
					<li>Ton déguisement</li>
					<li>Des vêtements qui ne craignent rien</li>
					<li>Des vêtements qui tiennent chaud</li>
					<li>Une boîte à clou !</li>
					<li>Un k-way</li>
					<li>Ton autorisation parentale si tu es mineur</li>
				</ul>
			</div>
		</div>
    @elseif((new DateTime(Config::get('services.wei.registrationStart'))) > (new DateTime()))
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Ouverture des inscriptions pour le week-end dans ...</h3>
            </div>
            <div class="box-body text-center">
                <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
    			<big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}</big>
            </div>
        </div>
    @else
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Les inscriptions pour le week-end sont fermés</h3>
            </div>
        </div>
	@endif


@endsection