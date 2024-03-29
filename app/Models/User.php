<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Crypt;
use Config;
use Ramsey\Uuid\Uuid;

class User extends Model implements Authenticatable
{
    use HasApiTokens, Notifiable;

    public $table = 'users';
    public $timestamps = true;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    const ADMIN_NOT = 0;
    const ADMIN_MODERATOR = 50;
    const ADMIN_FULL = 100;

    public $primaryKey = 'id';

    protected $attributes = [
        'volunteer_preferences' => '[]',
        'remember_token' => '',
    ];

    public $fillable = [
        'student_id',
        'admitted_id',
        'first_name',
        'last_name',
        'sex',
        'birth',
        'surname',
        'email',
        'discord',
        'phone',
        'postal_code',
        'city',
        'country',
        'branch',
        'level',
        'referral_text',
        'referral_max',
        'volunteer',
        'facebook',
        'team_id',
        'ce',
        'registration_email',
        'registration_phone',
        'referral_id',
        'parent_name',
        'parent_phone',
        'medical_allergies',
        'medical_treatment',
        'medical_note',
        'is_newcomer',
        'device_token',
        'latitude',
        'longitude',
        'bus_id',
        'wei_majority',
        'mission_order',
        'mission_respo',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'volunteer_preferences' => 'array',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    public $dates = [
        'created_at',
        'updated_at',
        'birth'
    ];

    public $checklistArray = [];

    const CHECKLIST_DEFINITION = [
        'profil_email' => [
            'action' => 'Compléter ton email',
            'page' => 'profil',
        ],
        'profil_phone' => [
            'action' => 'Compléter ton numéro de téléphone',
            'page' => 'profil',
        ],
        'profil_parent_name' => [
            'action' => 'Compléter le nom de ton contact d\'urgence',
            'page' => 'profil',
        ],
        'profil_parent_phone' => [
            'action' => 'Compléter le numéro de ton contact d\'urgence',
            'page' => 'profil',
        ],
        'referral' => [
            'action' => 'Prendre contact avec ton parrain',
            'page' => 'referral',
        ],
        'team_disguise' => [
            'action' => 'Rejoindre le channel discord de ton équipe et faire ton déguisement ',
            'page' => 'team',
        ],
        'app_download' => [
            'action' => 'Rejoindre les réseaux sociaux de l\'Intégration',
            'page' => 'app',
        ],
        'back_to_school' => [
            'action' => 'Pause partenaires !',
            'page' => 'backtoschool',
        ],
        'wei_pay' => [
            'action' => 'T\'inscrire pour le Week-End d\'Intégration',
            'page' => 'wei',
        ],
        'wei_guarantee' => [
            'action' => 'Déposer la caution',
            'page' => 'wei',
        ],
        'wei_authorization' => [
            'action' => 'Déposer l\'autorisation parentale',
            'page' => 'wei',
        ],
    ];

    const VOLUNTEER_PREFERENCES = [
        'anim' => [
            'title' => 'Animation',
            'description' => 'Animer, divertir et motiver les CE et les nouveaux étudiants tout au long de la pré-inté et de l’inté. Et pourquoi on est là ??',
        ],
        'aprem_chill' => [
            'title' => 'Aprèm Chill',
            'description' => 'Organiser une après-midi en proposant différentes activités, le tout en restant chill !',
        ],
        'benevole' => [
            'title' => 'Bénévole',
            'description' => 'Tu n’es pas là avant la semaine d’intégration mais tu souhaites aider pour mettre en place du matériel, cuisiner et faire pleins d’autres trucs pendant la semaine ? Deviens bénévole !',
        ],
        'bouffe' => [
            'title' => 'Bouffe',
            'description' => 'Prévoir, organiser et coordonner tous les repas de l’inté. La bouffe c’est sacré !',
        ],
        'cahier_vacances' => [
            'title' => 'Cahier de vacances',
            'description' => 'Élaborer le futur cahier de vacances des nouveaux avec des petits exercices et blagues pour les motiver pendant l’été.',
        ],
        'ce' => [
            'title' => 'Chef d’équipe (CE)',
            'description' => 'Par groupe de 4/5 étudiants, être en charge d’une équipe d’environ 15 à 20 nouveaux étudiants, s’occuper d’eux, participer aux événements avec eux, réaliser des défis en équipe, répondre à leurs questions... En bref, les accompagner autant que possible pendant leur première semaine à l’UTT.',
        ],
        'comm' => [
            'title' => 'Communication & Graphisme',
            'description' => 'Préparer et gérer toute la communication relative à l’intégration (stratégie, réseaux sociaux, plans de com, affiches, etc). Créer une charte une charte graphique liée au thème.',
        ],
        'deco' => [
            'title' => 'Déco',
            'description' => 'Être créatif et fabriquer de quoi décorer l’UTT au thème de l’inté.',
        ],
        'defis' => [
            'title' => 'Défis TC',
            'description' => 'Préparer un défi d’une aprèm où les nouveaux TC devront faire preuve d’ingéniosité pour fabriquer quelque chose à partir de peu (carton, marshmallow...).',
        ],
        'dev' => [
            'title' => 'Dev / Info',
            'description' => 'Maintenir le site et l’application de l’inté et développer de nouveaux outils informatiques',
        ],
        'faux_cours' => [
            'title' => 'Amphi de remédiation',
            'description' => 'Créer un faux premier cours compliqué pour les TC avec des professeurs et des faux élèves durant la première semaine.',
        ],
        'faux_disc' => [
            'title' => 'Discours de rentrée',
            'description' => 'Préparer et faire un discours de rentrée pour faire une petite frayeur aux nouveaux.',
        ],
        'gof' => [
            'title' => 'Games of Fondation',
            'description' => 'Organiser une aprèm type kermesse en partenariat avec la Fondation UTT.',
        ],
        'gubu' => [
            'title' => 'GUBU',
            'description' => 'Élaborer le futur GUBU des nouveaux pour faire vivre l’esprit UTTien avec des histoires, des recettes, des chants, des vannes, parler des assos...',
        ],
        'log' => [
            'title' => 'Logistique',
            'description' => 'Préparer, organiser et mettre en place tout le matériel nécessaire avant et pendant la semaine d’intégration.',
        ],
        'nutt' => [
            'title' => 'NUTT',
            'description' => 'Rédiger le journal étudiant de l’UTT avec des anecdotes et des jeux en rapport avec la vie étudiante UTTienne.'
        ],
        'media' => [
            'title' => 'Médiatik',
            'description' => 'Couvrir l’ensemble des événements de l’intégration, prendre des photos, et monter des films pour laisser à tout le monde de beaux souvenirs.',
        ],
        'parrainage' => [
            'title' => 'Parrainage',
            'description' => 'Attribuer des parrains/marraines aux nouveaux étudiants de manière personnalisée.',
        ],
        'partenariat' => [
            'title' => 'Partenariat',
            'description' => 'Rechercher et établir des partenariats utiles pour l\'intégration et pour les nouveaux étudiants',
        ],
        'prev' => [
            'title' => 'Prévention',
            'description' => 'Évaluer les risques et mettre en place des mesures préventives pour les orgas, CE et nouveaux avant et pendant l’intégration.',
        ],
        'rallye' => [
            'title' => 'Rallye',
            'description' => 'Organiser une aprèm de folie pour les nouveaux avec pleins de jeux, d’activités sportives et autres idées que vous avez !',
        ],
        'respo_ce' => [
            'title' => 'Respo CE',
            'description' => 'Gérer l’ensemble du planning des CE (shotgun, perms, planning...) et leur donner les grandes lignes directrices pour qu’ils accueillent au mieux les nouveaux. Tu es moteur indispensable à l’animation de l’inté !!!!',
        ],
        'respo_nouveaux' => [
            'title' => 'Respo Nouveaux/bénévoles',
            'description' => 'Venir en aide aux nouveaux qui auraient des problèmes administratifs et répondre à leurs questions durant l’été notamment sur discord et gérer les bénévoles pendant la semaine d’inté',
        ],
        'salon_vie_etudiante' => [
            'title' => 'Salon Vie Etudiante',
            'description' => 'Organiser les Salons de la Vie Etudiante la 2ème semaine de l\'inté avec les différents acteurs de la vie étudiante de l\'UTT.',
        ],
        'secu' => [
            'title' => 'Sécu',
            'description' => 'Gérer la sécurité des évènements notamment durant le WEI et la soirée d’inté.',
        ],
        'soiree' => [
            'title' => 'Soirée d\'intégration',
            'description' => 'Préparer et organiser une soirée sur le campus de l’UTT durant la semaine d’inté (organisation des boissons, de la nourriture, du vestiaire, de la sécurité, des animations, etc). Vous serez en relation avec les professionnels de la sécurité pour encadrer l’événement.',
        ],
        'son_lumiere' => [
            'title' => 'Son et lumière',
            'description' => 'Prévoir, installer et gérer les éléments de S&L durant les événements qui le nécessitent (soirée d’inté, WEI, espace chill, etc).',
        ],
        'soutenabilite' => [
            'title' => 'Soutenabilité',
            'description' => 'Mettre en place des actions pour réduire l’impact environnemental de l’inté en étant formé : amélioration des commissions, bilan carbone, ateliers de sensibilisation, ...'
        ],
        'trad' => [
            'title' => 'Traduction en anglais',
            'description' => 'Participer à la traduction des contenus publiés par l’intégration en anglais (à destination des étudiants étrangers qui participeront également à cette semaine d’inté).',
        ],
        'village' => [
            'title' => 'Village Asso',
            'description' => 'Organiser une aprèm en collaboration avec des associations UTTiennes afin de les présenter à travers de petites activités et coordonner l’évènement le jour J.',
        ],
	    'VIS' => [
            'title' => 'Village des initiatives soutenables',
            'description' => 'Organiser une aprèm autour des initiatives soutenables avec des stands d’associatifs UTTiens et d’acteurs locaux (Troyes Champagne Métropole, TCAT, la Maison des Étudiants...).',
        ],
	    'vmd' => [
            'title' => 'Village de la Mobilité Douce',
            'description' => ' Organiser un événement autour des mobilités soutenables avec différents stands et acteurs locaux (TCM, La Roue Verte, TCAT, la MDE, ...)',
        ],
        'visites' => [
            'title' => 'Visites',
            'description' => 'Organiser les visites du samedi aprèm avant l\'inté et celles pendant la première semaine pour les TC et les branches.',
        ],
        'wei' => [
            'title' => 'WEI',
            'description' => 'Organiser le Week-end d’intégration (transport, animation, logistique, soirée, boissons) en coordonnant avec les différentes commissions de l’inté : log, bouffe, S&L, anim...',
        ],
    ];

    public function hasAlreadyValidatedChallenge(int $id) {
        return count($this->challenges()->where('challenges.id', '=', $id)->wherePivot('validated', true)->get())>0?true:false;
    }

    public function challenges() {
        $pivots = ['submittedOn', 'validated', 'proof_url', 'last_update', 'update_author', 'message', 'team_id'];
        return $this->belongsToMany('App\Models\Challenge', 'challenge_validations')->withPivot($pivots)->where('user_id', '=', $this->id);
    }

    /**
     * Return password type
     */

    public function getPasswordType()
    {
        return password_get_info($this->password);
    }

    /**
     * Change the identifier for passport ('email' field by default, we want 'login')
     *
     * @param String $identifier the value of the 'username' parameter sent in the request
     * @return User
     */
    public static function findForPassport($identifier) {
        return User::where('login', $identifier)->first();
    }

    /**
     * Rewrite the way passport check if the password is right (because we encrypt the newcomer's passwords)
     *
     * @param String $password: the password sent as in the request
     * @return Boolean
     */
    public function validateForPassportPasswordGrant($password) {
        // decrypt the password of the user to compare it
        return password_verify($password, $this->password);
    }

    /**
     * Encrypt the given password and associate it to the user
     *
     * @param String $password: the password to set
     */
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /*
     * If code postal is null then it's 0
     */
    public function getPostalCodeAttribute($value)
    {
        if(!$value)
            return 0;
        return $value;
    }

    /*
     * Accessors mail
     */
    public function getBestEmail()
    {
        if ($this->email) {
            return $this->email;
        }
        return $this->registration_email;
    }

    /**
     * Check if user is underaged for the wei start
     * @return boolean true if the user is underage for the wei
     */
    public function isUnderage()
    {
        if($this->birth) {
            return ($this->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));
        }
        else if ($this->wei_majority !== null) {
            return !$this->wei_majority;
        }
        else if ($this->isStudent()) {
            return false;
        }
        return true;
    }

    /**
     * Scope a query to only include users that are newcomers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewcomer($query)
    {
        return $query->where('is_newcomer', true);
    }

    /**
     * Scope a query to only include users that are students.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudent($query)
    {
        return $query->where('is_newcomer', false)->whereNotNull('etuutt_login');
    }

    /**
     * Query referrals newscomers
     */
    public function newcomers()
    {
        return $this->hasMany(User::class, 'referral_id', 'id');
    }

    /**
     * Define the One-to-Many relation with Message;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function isStudent()
    {
        return !$this->is_newcomer && $this->etuutt_login;
    }

    public function isNewcomer()
    {
        return $this->is_newcomer;
    }

    /**
     * Return newcomers referral
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function godFather()
    {
        return $this->belongsTo(User::class, 'referral_id', 'id');
    }

    public function mailHistories()
    {
        return $this->hasMany(MailHistory::class);
    }
    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_login', 'birth'];
    }

    /**
     * The chekins that belong to the User.
     */
    public function students()
    {
        return $this->belongsToMany(Checkin::class);
    }

    /**
     * Define the One-to-Many relation with Team;
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    /**
     * Test if the user can all of the dashboard
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->admin == User::ADMIN_FULL);
    }

    /**
     * Test if the user can all of the dashboard
     * @return bool
     */
    public function isModerator()
    {
        return ($this->admin == User::ADMIN_MODERATOR);
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weiPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sandwichPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'sandwich_payment');
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        $name = $this->getAuthIdentifierName();

        return $this->attributes[$name];
    }

    /**
     * Retourne le secret d'authentification
     */

    public function getHashAuthentification()
    {
        return sha1($this->registration_email.$this->created_at->timestamp);
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        // return $this->attributes['password'];
        return NULL;
    }

    /**
     * Set the "remember me" token value.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->attributes[$this->getRememberTokenName()];
    }

    /**
     * Generate a rememberable password
     * @return string password
     */
    public static function generatePassword()
    {
        $consonant = 'bcdfgjklmnpqrstvwxz';
        $vowel = 'aeiou';
        $countC = mb_strlen($consonant);
        $countV = mb_strlen($vowel);
        $result = '';

        for ($i = 0, $result = ''; $i < 4; $i++) {
            $index = mt_rand(0, $countC - 1);
            $result .= mb_substr($consonant, $index, 1);

            $index = mt_rand(0, $countV - 1);
            $result .= mb_substr($vowel, $index, 1);
        }

        return $result;
    }

    public function isChecked($element)
    {
        return (!empty($this->getChecklist()[$element]));
    }

    public function isPageChecked($page)
    {
        foreach ($this->getChecklist() as $key => $value) {
            if (self::CHECKLIST_DEFINITION[$key]['page'] == $page && !$value) {
                return false;
            }
        }
        return true;
    }

    public function setCheck($element, bool $bool = true)
    {
        $checklist = $this->getChecklist();
        $checklist[$element] = $bool;
        $this->setChecklist($checklist);
    }

    public function getChecklistPercent()
    {
        $count = 0;
        foreach ($this->getChecklist() as $value) {
            if (!empty($value)) {
                $count++;
            }
        }
        return floor(($count/(count($this->getChecklist()) - 2))*100);
    }

    public function getNextCheck()
    {
        $count = 0;
        foreach ($this->getChecklist() as $key => $value) {
            if (empty($value)) {
                return self::CHECKLIST_DEFINITION[$key];
            }
        }
        return [
            'page' => 'done',
            'action' => 'Aucune !'
        ];
    }

    /**
     * Set the checklist array
     *
     * @return string
     */
    public function setChecklist(array $checklist)
    {
        $this->checklistArray = $checklist;
        $this->checklist = serialize($this->checklistArray);
    }

    /**
     * Get the checklist array
     *
     * @return string
     */
    public function getChecklist()
    {
        if (empty($this->checklistArray)) {
            $array = unserialize($this->checklist);
            if (empty($array)) {
                $array = [];
            }
            $definition = array_fill_keys(array_keys(self::CHECKLIST_DEFINITION), false);
            $this->checklistArray = array_merge($definition, $array);
        };
        return $this->checklistArray;
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guaranteePayment()
    {
        return $this->belongsTo('App\Models\Payment', 'guarantee_payment');
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function boot()
    {
        parent::boot();

        User::creating(function ($user) {
            // Generate login
            if (empty($user->login)) {
                $login = strtolower(mb_substr(mb_substr(preg_replace("/[^A-Za-z0-9]/", '', $user->first_name), 0, 1) . preg_replace("/[^A-Za-z0-9]/", '', $user->last_name), 0, 8));
                $i = '';
                while (User::where(['login' => $login . $i])->count()) {
                    if (empty($i)) {
                        $i = 1;
                    }
                    $i++;
                }
                $user->login = $login . $i;
            }

            // generate uuid
            $user->qrcode = Uuid::uuid4();
        });
    }

    public function updateWei()
    {
        $weiPayment = $this->weiPayment && in_array($this->weiPayment->state, ['paid', 'returned']);
        $guaranteePayment = $this->guaranteePayment && in_array($this->guaranteePayment->state, ['paid', 'returned']);

        // only if it's a newcomer
        if ($this->is_newcomer) {
            if (Config::get('services.wei.open') === '-1') {
                $this->setCheck('wei_pay', true);
                $this->setCheck('wei_guarantee', true);
                $this->setCheck('wei_authorization', true);
            } else {
                $this->setCheck('wei_pay', $weiPayment);
                $this->setCheck('wei_guarantee', $guaranteePayment);

                if (!$this->isUnderage()) {
                    $this->setCheck('wei_authorization', true);
                    $this->parent_authorization = true;
                } elseif ($this->parent_authorization) {
                    $this->setCheck('wei_authorization', true);
                } else {
                    $this->setCheck('wei_authorization', false);
                }
            }
        }

        $wei = ($weiPayment || $guaranteePayment);
        if ($this->wei != $wei) {
            $this->wei = $wei;
        }
        $this->save();
    }

    public function isOrga() : bool {
        return $this->orga?true:false;
    }


    /**
     * The perms of the User.
     */
    public function perms()
    {
      return $this->belongsToMany(Perm::class, 'perm_users', 'user_id', 'perm_id')
        ->wherePivot('respo', false)
        ->withPivot('presence')
        ->withPivot('pointsPenalty')
        ->withPivot('commentary')
        ->withPivot('absence_reason');
    }
    /**
     * Points of the User.
     */
    public function points()
    {
      $points = 0;
      foreach ($this->perms as $perm) {
        $points += $perm->type->points;
        $points -= $perm->pivot->pointsPenalty;
      }
      return $points;
    }
    /**
     * Absences of the User.
     */
    public function absences()
    {
      return $this->belongsToMany(Perm::class, 'perm_users', 'user_id', 'perm_id')
        ->wherePivot('respo', false)
        ->wherePivot('presence', 'absent');
    }
    /**
     * Presence of the User.
     */
    public function presences()
    {
      return $this->belongsToMany(Perm::class, 'perm_users', 'user_id', 'perm_id')
        ->wherePivot('respo', false)
        ->wherePivot('presence', 'present');
    }

    public function devices() {
        return $this->hasMany('App\Models\Device');
    }

    /**
     * The branch of the User
     */

    public function isTC()
    {
        return ( $this->branch == 'TC');
    }

    public function isBranch()
    {
        return in_array( $this->branch, array('RT', 'ISI', 'GI', 'A2I', 'GM', 'MTE', 'GM_APPR', 'GI_APPR', 'SN_APPR') );
    }

    public function isMaster()
    {
        return in_array( $this->branch, array('ISC','PAIP-GS', 'RE') );
    }

    public function nbReferrals()
    {
        $userId=$this->id;
        $nbReferrals=DB::table('users')
            ->where('referral_id', '=', $userId)
            ->count();
        return $nbReferrals;
    }

    public function nbTCReferrals()
    {
        $userId = $this->id;
        $nbTCReferrals = DB::table('users')
            ->where('referral_id', '=', $userId)
            ->where('branch', '=','TC')
            ->count();
        return $nbTCReferrals;
    }

    public function nbBranchReferrals()
    {
        $userId = $this->id;
        $nbBranchReferrals = DB::table('users')
            ->where('referral_id', '=', $userId)
            ->whereIn('branch', ['RT','ISI', 'GI', 'A2I', 'GM', 'MTE'])
            ->count();
        return $nbBranchReferrals;
    }

    public function nbMasterReferrals()
    {
        $userId = $this->id;
        $nbMasterReferrals = DB::table('users')
            ->where('referral_id', '=', $userId)
            ->whereIn('branch', ['ISC','PAIP', 'RE'])
            ->count();
        return $nbMasterReferrals;
    }

}
