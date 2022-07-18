<?php

namespace App\Console\Commands;

use App\Imports\KatosImport;
use App\Models\Kato;
use App\Modules\Kato\Import;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportKato extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kato:import {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First kato imports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = storage_path($this->option('file'));
        $collection = Excel::toCollection(new KatosImport, $path);
        (new Import)->importFromFile($collection[0], 'import');

        $katos = Kato::where('level', '>', 2)->where('level', '<=', 6)->get();
        (new Import)->katoPostUpdate($katos, 'import');


        $this->info('Imported file: '.$path); // Notify to terminal
        \Log::info('Imported file: '.$path); // Writing to the logs
    }
}
