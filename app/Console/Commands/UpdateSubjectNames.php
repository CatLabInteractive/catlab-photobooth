<?php

namespace App\Console\Commands;

use App\Models\Subject;
use Illuminate\Console\Command;

class UpdateSubjectNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subjects:updateNames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Try to look up subject names.';

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
    public function handle()
    {
        foreach (Subject::all() as $subject) {
            $subject->updateSubjectName();
            $this->output->writeln('Set name for subject ' . $subject->id . ' to ' . $subject->name);
        }
    }
}
