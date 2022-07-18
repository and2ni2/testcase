<?php

namespace App\Console\Commands;

use App\Imports\KatosImport;
use App\Models\Kato;
use App\Modules\Kato\Import;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class UpdateKato extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kato:update {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Kato data in DB by import';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = storage_path($this->option('file'));
        $collection = Excel::toCollection(new KatosImport, $path);
        $today_date = Carbon::now()->format('Y-m-d');

        Kato::where('end_date', null)->update(['end_date' => $today_date]);
        (new Import)->importFromFile($collection[0], 'update');

        $katos = Kato::whereDate('end_date', '=', $today_date)->get();
        (new Import)->katoPostUpdate($katos, 'update');


        $this->info('Updated file: '.$path); // Notify to terminal
        \Log::info('Updated file: '.$path); // Writing to the logs
    }
}
