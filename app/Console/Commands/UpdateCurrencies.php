<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command update all currencies in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $original_currencies = simplexml_load_string(Http::get('https://nationalbank.kz/rss/rates_all.xml?switch=russian')); // Get XML and convert to JSON
        $clean_currencies_data = $original_currencies->channel->item; // Get only needed data

        foreach ($clean_currencies_data as $item) {
            Currency::updateOrCreate([
                'name' => $item->title,
                'date' => Carbon::createFromFormat('d.m.Y', $item->pubDate)
            ],
            ['rate' => $item->description]
            );
        }

        $this->info('Currencies was updated'); // Notify to terminal
        \Log::info('Currencies was updated'); // Writing to the logs

    }
}
