@extends('layouts.dashboard')

@section('title')
Mon profil bénévole
@endsection

@section('smalltitle')
Parce que l'intégration, c'est surtout vous !
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification de mon profil</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.students.profil') }}" method="post">
            <fieldset>
                <div class="col-lg-12">
                    <p class="text-justify">
                        En devenant bénévole, vous serez ajouté à la liste de mails à contacter pour demander des coups de main pour l'intégration (stupre-liste).<br/>
                        Si tu veux donner plus qu'un coup de main pour l'intégration et que tu es dispo fin août, envoi un mail à <a href="mailto:integration@utt.fr">integration@utt.fr</a>. Promis, on est gentils. :)
                    </p>
                </div>
            </fieldset>
            <fieldset>
                <legend>Tes informations</legend>

                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">Nom complet</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" disabled value="{{{ $student->first_name . ' ' . $student->last_name }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="surname" class="col-lg-2 control-label">Surnom</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" name="surname" id="surname" value="{{{ old('surname') ?? Auth::user()->surname }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="sex" class="col-lg-2 control-label">Sexe</label>
                    <div class="col-lg-10">
                        <select id="sex" name="sex" class="form-control" class="">()
                            <option value="0" @if (( old('sex') ?? Auth::user()->sex ) == 0) selected="selected" @endif >Homme</option>
                            <option value="1" @if (( old('sex') ?? Auth::user()->sex ) == 1) selected="selected" @endif >Femme</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Mail</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="email" id="email" placeholder="Adresse mail" type="text" value="{{{ old('email') ?? Auth::user()->email }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="col-lg-2 control-label">Portable</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="phone" id="phone" placeholder="Portable" type="text" value="{{{ old('phone') ?? Auth::user()->phone }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="discord" class="col-lg-2 control-label">Discord</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="discord" id="discord" placeholder="Tag Discord" pattern=".*(#[0-9]{4})?" type="text" value="{{{ old('discord') ?? Auth::user()->discord }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="semester" class="col-lg-2 control-label">Semestre en cours</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="semester" id="semester" type="text" value="{{{ $student->branch . $student->level }}}" disabled>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Ce que tu aimerais faire</legend>
                <div class="col-lg-12">
                    <p class="text-justify">
                        Afin qu'on puisse demander ton aide en fonction de ce que as envie de faire, nous avons besoin d'en savoir un peu plus sur tes préférences.<br/>
                        Tu peux cocher plusieurs cases, ça n'engage à rien et tu n'auras pas moins de chance de faire chef d'équipe (ou autre) parce que tu coches d'autres cases, bien au contraire ;-)<br/>
                        Ne coche pas plus de 4 cases, plus tu en coches, moins ce sera facile pour nous de savoir ce que tu veux vraiment faire et donc de te proposer la commission que tu préfères.
                    </p>

                    <div class="form-group">
                        @foreach (\App\Models\User::VOLUNTEER_PREFERENCES as $key => $preference)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="volunteer_preferences[{{{ $key }}}]" @if (( old('volunteer_preferences.'.$key) ?? in_array($key, $student->volunteer_preferences ?? []) )) checked="checked" @endif/>
                                <strong>{{{ $preference['title'] }}}</strong><span class="hidden-xs"> : {{{ $preference['description'] }}}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>L'esprit de l'intégration</legend>
                <div class="form-group">
                    <div class="col-lg-12">
                        <p class="text-justify">
                            Le but de l'intégration est de faire découvrir aux nouveaux le monde étudiant, le fonctionnement et les locaux de l'école,
                            de faire former des groupes de potes chez les nouveaux, mais aussi et surtout d'<strong>envoyer du rêve</strong>.
                            Cette intégration, nous l'organisons <strong>pour les nouveaux, pas contre eux ni pour nous.</strong>.
                        </p>
                        <p class="text-justify">
                            En étant bénévole pour cette intégration, vous aurez automatiquement un rôle de modèle et d'exemple vis-à-vis des nouveaux.
                            C'est normal, vous avez déjà passé un peu de temps dans cette école et donc vous en savez plus. Cependant,
                            c'est dans cette situation que vous pouvez être amené à <strong>bizuter, sans parfois même vous en rendre compte ou sans la volonté de nuire</strong>.</p>
                        </p>

                        <ul class="hidden-xs">
                            <li>Si un nouveau ne veut pas faire quelque chose, vous ne devez pas insister pour qu'il le fasse.</li>
                            <li>Ce n'est pas parce qu'on vous l'a fait à votre intégration que c'est acceptable.</li>
                            <li>Ce n'est pas parce que vous trouvez cela drôle que cela l'est aussi pour les nouveaux.</li>
                            <li>Si vous êtes témoins de bizutage pendant l'intégration, ou que vous avez un doute si quelque chose est acceptable, contactez un coordinateur.</li>
							<li>Notez ce numéro d'urgence, à ne <strong>jamais</strong> utiliser pour jouer (c'est comme le 15 ou le 112) : 07.68.74.02.59.</li>
                        </ul>

                        <p class="text-justify">
                            <strong>Le bizutage est un <em>délit</em>, puni par la <em>loi</em>. Ceux qui le pratiquent risquent des peines de prison ou de fortes amendes.
                            En marge des poursuites judiciaires engagées, ils peuvent être présentés aux sections disciplinaires de l'UTT, avec des sanctions pouvant aller
                            jusqu'à l'exclusion définitive de tout établissement d'enseignement supérieur français.</strong>
                        </p>

                        <div class="well convention-well hidden-xs">
                            <p>
                                Extrait de la loi n°98-468 du 17 juin 1998<br/>
                                Section 3 bis – Du bizutage
                            </p>

                            <p>
                                «&nbsp;Article 225-16-1. Hors les cas de violences, de menaces ou d'atteintes sexuelles,
                                le fait pour une personne d'amener autrui, contre son gré ou non,
                                à subir ou à commettre des actes humiliants ou dégradants lors de manifestations ou
                                de réunions liées aux milieux scolaire et socio-éducatif est puni
                                de six mois d'emprisonnement et de 7 500 euros d'amende.&nbsp;»
                            </p>

                            <p>
                                «&nbsp;Article 225-16-2.L'infraction définie à l'article 225-16-1 est punie d'un an
                                d'emprisonnement et de 15 000 euros d'amende lorsqu'elle est commise sur
                                une personne dont la particulière vulnérabilité, due à son âge, à une maladie, à
                                une infirmité, à une déficience physique ou psychique ou à un état de
                                grossesse, est apparente ou connue de son auteur.&nbsp;»
                            </p>

                            <p>
                                «&nbsp;Article 225-16-3.Les personnes morales déclarées responsables pénalement, dans
                                les conditions prévues par l'article 121-2, des infractions définies aux
                                articles 225-16-1 et 225-16-2 encourent, outre l'amende suivant les modalités
                                prévues par l'article 131-38, les peines prévues par les 4° et 9° de l'article 131-39.&nbsp;»
                            </p>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="convention" name="convention" @if ($student->volunteer == 1) checked="checked" @endif/>
                                <strong>Je comprends l'objectif de l'intégration et je comprends que mes actions peuvent être punies par une sanction disciplinaire et une peine d'emprisonnement et 15 000 € d'amende.</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
    </div>
</div>


                    @endsection
