<?php

namespace App\Console\Commands;

use App\Audit\IsAuditable;
use Illuminate\Console\Command;

class GenerateAuditTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditable:checktables {--create} {--recreate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the table for audit';

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
        app()->make('auditableModels')->each(function(IsAuditable $model) {
            if(!$model->auditTableExists()) {
                $this->info("Table {$model->getAuditTable()} not exists.");
                if($this->option('create')) {
                    $model->createAuditTable();
                    $this->info("Table {$model->getAuditTable()} created.");
                } else {
                    $this->info("Table {$model->getAuditTable()} creation skipped.");
                }
            } else {
                $this->info("Table {$model->getAuditTable()} already exists.");
                if($this->option('recreate')) {
                    $model->dropAuditTable();
                    $this->info("Table {$model->getAuditTable()} dropped.");
                    $model->createAuditTable();
                    $this->info("Table {$model->getAuditTable()} created.");
                }
            }
        });

        return true;
    }
}
