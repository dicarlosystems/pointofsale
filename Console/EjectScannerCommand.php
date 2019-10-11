<?php

namespace Modules\PointOfSale\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EjectScannerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pointofsale:eject-scanner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the include for the scanner functionality from the invoice view(s)';

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
        // add the scanner include to the invoices/edit.blade.php file
        $editInvoiceView = base_path('resources/views/invoices/edit.blade.php');

        $contents = file_get_contents($editInvoiceView);

        
        //$lines = file($editInvoiceView);
        $include = "@include('pointofsale::invoices.edit')@stop";
        $contents = str_replace($include, '@stop', $contents);
        //$numberOfLines = count($lines);

        // for($i = 0; $i < $numberOfLines; $i++) {
        //     if($lines[$i] == $include) {
        //         unset($lines[$i]);
        //     }
        // }

        // dump($lines);
        file_put_contents($editInvoiceView, $contents);
    }
}
