<?php
namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Illuminate\Console\Command;

class FindCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find translations in php/twig files';

    /** @var  \Botble\Translation\Manager */
    protected $manager;

    /**
     * FindCommand constructor.
     * @param Manager $manager
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return void
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function fire()
    {
        $counter = $this->manager->findTranslations();
        $this->info('Done importing, processed ' . $counter . ' items!');

    }
}
