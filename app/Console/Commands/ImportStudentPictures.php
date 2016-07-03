<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Student;

class ImportStudentPictures extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'students:importPictures';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import students pictures.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		foreach (Student::all() as $student)
		{
			$picture = file_get_contents('http://local-sig.utt.fr/Pub/trombi/individu/' . $student->student_id . '.jpg');
			file_put_contents(public_path() . '/uploads/students-trombi/' . $student->student_id . '.jpg', $picture);
		}
		$this->info('Done!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}