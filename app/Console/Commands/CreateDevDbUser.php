<?php

namespace Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDevDbUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:dbuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a dev user with all privileges';

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
        config()->set('database.connections.root.password', file_get_contents('.root_db'));

        $root = DB::reconnect('root');

        $root->statement("CREATE USER 'dev'@'%' IDENTIFIED BY 'dev'");
        $root->statement("GRANT ALL ON *.* TO 'dev'@'%'");
        $root->statement("FLUSH PRIVILEGES");

        DB::disconnect();
        $this->warn('Done.');
    }
}
