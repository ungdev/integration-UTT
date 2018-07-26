<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {

	public $timestamps=false;

	public $fillable = [
		"name",
		"description",
		"points",
		"deadline"
	];

	/**
	 * All the teams that asked validation for this challenge
	 * whether it is accepted or not
	 */
	public function teams() {
		/**
		 * validated can have 3 values :
		 *  -1: refused
		 *  0: pending
		 *  1: accepted
		 *  Yep, a boolean should have been used, but my first solution was to use
		 *  false: refused
		 *  true: accepted
		 *  null: pending
		 *  and laravel (at least in 5.2) doesn't seem to differenciate null and false
		 */
		$pivots = ["submittedOn", "validated", "pic_url", "last_update"];
		return $this->belongsToMany("App\Models\Team", "challenge_validations")->withPivot($pivots);
	}

}