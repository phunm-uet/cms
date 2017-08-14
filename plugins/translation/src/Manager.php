<?php

namespace Botble\Translation;

use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Botble\Translation\Models\Translation;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Lang;
use Symfony\Component\Finder\Finder;

class Manager
{

    /** @var \Illuminate\Foundation\Application */
    protected $app;
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $files;
    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    protected $config;

    /**
     * Manager constructor.
     * @param Application $app
     * @param Filesystem $files
     * @param Dispatcher $events
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function __construct(Application $app, Filesystem $files, Dispatcher $events)
    {
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
        $this->config = $app['config']['translation-manager'];
    }

    /**
     * @param bool $replace
     * @return int
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function importTranslations($replace = false)
    {
        $counter = 0;
        foreach ($this->files->directories($this->app->langPath()) as $langPath) {
            $locale = basename($langPath);

            foreach ($this->files->allFiles($langPath) as $file) {

                $group = File::name($file);

                if (in_array($group, array_get($this->config, 'exclude_groups', []))) {
                    continue;
                }

                $subLangPath = str_replace($langPath . DIRECTORY_SEPARATOR, '', File::dirname($file));

                $lang_directory = $group;
                if ($subLangPath != $langPath) {
                    $lang_directory = $subLangPath . '/' . $group;
                    $group = substr($subLangPath, 0, -3) . '/' . $group;
                }

                $translations = Lang::getLoader()->load($locale, $lang_directory);
                if ($translations && is_array($translations)) {
                    foreach (array_dot($translations) as $key => $value) {
                        // process only string values
                        if (is_array($value)) {
                            continue;
                        }
                        $value = (string)$value;
                        $translation = Translation::firstOrNew([
                            'locale' => $locale != 'vendor' ? $locale : substr($subLangPath, -2),
                            'group' => $group,
                            'key' => $key,
                        ]);

                        // Check if the database is different then the files
                        $newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
                        if ($newStatus !== (int)$translation->status) {
                            $translation->status = $newStatus;
                        }

                        // Only replace when empty, or explicitly told so
                        if ($replace || !$translation->value) {
                            $translation->value = $value;
                        }

                        $translation->save();

                        $counter++;
                    }
                }
            }
        }
        return $counter;
    }

    /**
     * @param null $path
     * @return int
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function findTranslations($path = null)
    {
        $path = $path ?: base_path();
        $keys = [];
        $functions = ['trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice'];
        $pattern =                              // See http://regexr.com/392hu
            '[^\w|>]' .                          // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .  // Must start with one of the functions
            '\(' .                               // Match opening parenthese
            '[\'\']' .                           // Match ' or '
            '(' .                                // Start a new group to match:
            '[a-zA-Z0-9_-]+' .               // Must start with group
            '([.][^\1)]+)+' .                // Be followed by one or more items/keys
            ')' .                                // Close group
            '[\'\']' .                           // Closing quote
            '[\),]';                            // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->name('*.php')->name('*.twig')->files();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all('/' . $pattern . '/siU', $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $keys[] = $key;
                }
            }
        }
        // Remove duplicates
        $keys = array_unique($keys);

        // Add the translations to the database, if not existing.
        foreach ($keys as $key) {
            // Split the group and item
            list($group, $item) = explode('.', $key, 2);
            $this->missingKey($group, $item);
        }

        // Return the number of found translations
        return count($keys);
    }

    /**
     * @param $group
     * @param $key
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function missingKey($group, $key)
    {
        if (!in_array($group, config('translations.exclude_groups', []))) {
            Translation::firstOrCreate([
                'locale' => $this->app['config']['app.locale'],
                'group' => $group,
                'key' => $key,
            ]);
        }
    }

    /**
     * @param $group
     * @return boolean
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function exportTranslations($group)
    {
        if (empty($this->config['exclude_groups']) || !in_array($group, $this->config['exclude_groups'])) {
            if ($group == '*') {
                $this->exportAllTranslations();
                return true;
            }

            $tree = $this->makeTree(Translation::where('group', $group)->whereNotNull('value')->get());

            foreach ($tree as $locale => $groups) {
                if (isset($groups[$group])) {
                    $translations = $groups[$group];
                    $file = $locale . '/' . $group;
                    $groups = explode('/', $group);
                    if (count($groups) > 1) {
                        $dir = '/vendor/' . $groups[0] . '/' . $locale;
                        if (!$this->files->isDirectory($this->app->langPath() . '/' . $dir)) {
                            $this->files->makeDirectory($this->app->langPath() . '/' . $dir, 755, true);
                            system('find ' . $this->app->langPath() . '/' . $dir . ' -type d -exec chmod 755 {} \;');
                        }
                        $file = $dir . '/' . $groups[1];
                    }
                    $path = $this->app->langPath() . '/' . $file . '.php';
                    $output = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
                    $this->files->put($path, $output);
                }
            }
            Translation::where('group', $group)->whereNotNull('value')->update(['status' => Translation::STATUS_SAVED]);
        }
        return true;
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function exportAllTranslations()
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                $select = 'DISTINCT `group`';
                break;

            default:
                $select = 'DISTINCT "group"';
                break;
        }

        $groups = Translation::whereNotNull('value')->select(DB::raw($select))->get('group');

        foreach ($groups as $group) {
            $this->exportTranslations($group->group);
        }
    }

    /**
     * @param $translations
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    protected function makeTree($translations)
    {
        $array = [];
        foreach ($translations as $translation) {
            array_set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
        }
        return $array;
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function cleanTranslations()
    {
        Translation::whereNull('value')->delete();
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function truncateTranslations()
    {
        Translation::truncate();
    }

    /**
     * @param null $key
     * @return mixed
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function getConfig($key = null)
    {
        if ($key == null) {
            return $this->config;
        } else {
            return $this->config[$key];
        }
    }
}
