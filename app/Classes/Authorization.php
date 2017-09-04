<?php

namespace App\Classes;

use App\Models\Team;
use App\Models\Student;
use Config;
use Auth;

/**
 * Authorization helper
 */
class Authorization
{

    /**
     * The current DateTime
     *
     * @var \DateTime
     */
    protected $now;

    /**
     * The current DateTime
     *
     * @return \DateTime
     */
    public function now()
    {
        if (!$this->now) {
            $this->now = new \DateTime();
        }
        return $this->now;
    }

    /**
     * Check if current user is authorized against a group and a facultative action
     * @param  string  $group  Group of user : admin, ce, orga, referral, student
     * @param  string  $action  Specific action for this group of user
     *
     * @return bool
     */
    public function can($group, $action = '')
    {
        if ($group == 'newcomer') {
            // Check if newcomer is identified
            if (!Auth::check() || (Auth::user()->isStudent())) {
                return false;
            }

            // Action verification
            $count = Student::where('is_newcomer', 1)->where('wei', 1)->count();
            switch ($action) {
                case 'wei':
                    if (!Auth::user()->wei
                        && ($this->now() <= new \DateTime(Config::get('services.wei.registrationStart'))
                        || $this->now() >= new \DateTime(Config::get('services.wei.registrationEnd'))
                        || $count >= Config::get('services.wei.newcomerMax'))) {
                        return false;
                    }
                    break;
            }
        } else {
            // Login/student verification
            if (in_array($group, ['student', 'admin', 'orga', 'ce', 'referral', 'volunteer', 'moderator'])
                    && !\EtuUTT::isAuth()) {
                return false;
            }
            $student = \EtuUTT::student();

            // Volunteer verification
            if (in_array($group, ['admin', 'orga', 'ce', 'volunteer', 'moderator'])
                    && !\EtuUTT::student()->volunteer) {
                return false;
            }

            // Group verification
            if ($group == 'admin'
                    && !$student->isAdmin()) {
                return false;
            }
            // Group verification
            if ($group == 'moderator'
                    && !$student->isAdmin()
                    && !$student->isModerator()) {
                return false;
            }
            // Group verification
            if ($group == 'referral'
                    && !$student->referral) {
                return false;
            } elseif ($group == 'orga'
                    && !$student->orga) {
                return false;
            } elseif ($group == 'ce'
                    && !$student->ce) {
                return false;
            }

            $teamCount = Team::count();
            // Action verification
            if ($group == 'ce') {
                switch ($action) {
                    case 'inTeam':
                        if (!$student->team) {
                            return false;
                        }
                        break;
                    case 'respo':
                        if (!$student->team || $student->team->respo_id != $student->student_id) {
                            return false;
                        }
                        break;
                    case 'create':
                        if ($this->now() > new \DateTime(Config::get('services.ce.deadline')) || $teamCount >= Config::get('services.ce.maxteam')
                            || $student->team) {
                            return false;
                        }
                        break;
                    case 'edit':
                        if (!$student->team || $student->team->respo_id != $student->student_id
                            || $this->now() > new \DateTime(Config::get('services.ce.deadline'))) {
                            return false;
                        }
			break;
                    case 'join':
                        if (!$student->team
                            || $this->now() > new \DateTime(Config::get('services.ce.deadline'))) {
                            return false;
                        }
                        break;
                }
            } elseif ($group == 'student') {
                switch ($action) {
		    case 'inTeam':
			if (!$student->team) {
			    return false;
			}
			break;
                    case 'referral':
                        if ($this->now() > new \DateTime(Config::get('services.referral.deadline'))
                            || $student->referral_validated
                            || $student->referral) {
                            return false;
                        }
                        break;
                    case 'ce':
                        if ($this->now() > new \DateTime(Config::get('services.ce.deadline')) || $teamCount >= Config::get('services.ce.maxteam')
                            || $student->ce) {
                            return false;
                        }
                        break;
                }
            } elseif ($group == 'referral') {
                switch ($action) {
                    case 'edit':
                        if ($this->now() > new \DateTime(Config::get('services.referral.deadline'))
                            || $student->referral_validated) {
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }

    /**
     * Return a countdown array for the given deadline
     * @param  string  $group  Group of user : admin, ce, orga, referral, student
     * @param  string  $action  Specific action for this group of user
     *
     * @return array
     */
    public function countdown($group, $action)
    {
        $date =  new \DateTime();

        // Action verification
        if ($group == 'ce') {
            switch ($action) {
                case 'create':
                    $date = new \DateTime(Config::get('services.ce.fakeDeadline'));
                    break;
                case 'edit':
                    $date = new \DateTime(Config::get('services.ce.fakeDeadline'));
                    break;
            }
        } elseif ($group == 'student') {
            switch ($action) {
                case 'referral':
                    $date = new \DateTime(Config::get('services.referral.fakeDeadline'));
                    break;
            }
        } elseif ($group == 'referral') {
            switch ($action) {
                case 'edit':
                    $date = new \DateTime(Config::get('services.referral.fakeDeadline'));
                    break;
            }
        }

        return (new \DateTime())->diff($date);
    }
}
