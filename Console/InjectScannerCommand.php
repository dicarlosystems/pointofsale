<?php

namespace Modules\PointOfSale\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InjectScannerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pointofsale:inject-scanner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the include for the scanner functionality to the invoice view(s)';

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
        
        $lines = file($editInvoiceView);
        $include = "@include('pointofsale::invoices.edit')";

        if($lines) {
            if(!in_array($include, $lines, false)) {
                array_pop($lines);
                array_push($lines, $include . "@stop");
                file_put_contents($editInvoiceView, $lines);
                $this->info("Injected scanner into invoice view(s).");
            } else {
                $this->info("Scanner already injected, no changes necessary.");
            }
        }
    }
}
