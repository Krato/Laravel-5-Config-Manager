<?php

namespace Infinety\ConfigManager\Controllers;

use ConfigHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RecursiveDirectoryIterator;

class ConfigManagerController extends Controller
{
    protected $configFiles;
    protected $formattedValues;

    public function __construct()
    {
        $this->configFiles = collect([]);
        $this->formattedValues = [];
    }

    public function index()
    {
        $this->getFiles(config_path());

        return $this->firstViewThatExists('vendor/infinety/configmanager/index', 'configmanager::index', ['configFiles' => $this->configFiles]);
    }

    public function view($file = null)
    {
        if ($file) {
            $data = ConfigHelper::readFile($file);
            $this->createTableFromArray($data->toArray());
            $this->getFiles(config_path());

            $file = $this->configFiles->filter(function ($item) use ($file) {
                if (str_contains($item->path, $file)) {
                    return $item;
                }
            });

            return $this->firstViewThatExists('vendor/infinety/configmanager/index', 'configmanager::index', ['configFiles' => $this->configFiles, 'fileData' => $file->first(), 'fileParsed' => $this->formattedValues]);
        }
    }

    public function update(Request $request)
    {
        $path = $request->get('filePath');
        $key = $request->get('key');
        $value = $request->get('value');
        $file = $this->getConfigPathFolder($path);

        ConfigHelper::save($file, $key, $value);

        return response()->json(true);
    }

    /**
     * Creates an array for html table.
     *
     * @param array  $configData
     * @param string $parent
     */
    private function createTableFromArray($configData, $parent = null)
    {
        foreach ($configData as $key => $configValue) {
            if (is_array($configValue)) {
                $this->createTableFromArray($configData[$key], ($parent) ? $parent.'.'.$key : $key);
            } else {
                $this->formattedValues[] = [
                    'key'   => ($parent) ? $parent.'.'.$key : $key,
                    'value' => $configValue,
                ];
            }
        }
    }

    /**
     * Get config files.
     *
     * @param string $dir
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function getFiles($dir)
    {
        $dir_iterator = new RecursiveDirectoryIterator($dir);
        foreach ($dir_iterator as $key => $file) {
            if (!is_dir($file->getRealpath())) {
                if ($this->accept($file)) {
                    $fileInfo = [
                        'name'   => trim($file->getBasename('.php')),
                        'path'   => $file->getPath().'/'.$file->getBasename(),
                        'parent' => ($file->getPathInfo()->getBasename() == 'config') ? null : $this->getConfigPathFolder($file->getPath()),
                    ];
                    $this->configFiles->push((object) $fileInfo);
                }
            } elseif ($this->accept($file)) {
                $data = $this->getFiles($file->getPath().'/'.$file->getBasename());
            }
        }
        $this->configFiles = $this->configFiles->sortBy('parent');
    }

    /**
     * Check if file has a dot in the first character so it's a hidden file.
     *
     * @param $file
     *
     * @return bool
     */
    private function accept($file)
    {
        return '.' !== substr($file->getBasename(), 0, 1);
    }

    public function getConfigPathFolder($path)
    {
        return str_replace(config_path().DIRECTORY_SEPARATOR, '', $path);
    }

    /**
     * Allow replace the default views by placing a view with the same name.
     * If no such view exists, load the one from the package.
     *
     * @param $first_view
     * @param $second_view
     * @param array $information
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function firstViewThatExists($first_view, $second_view, $information = [])
    {
        // load the first view if it exists, otherwise load the second one
        if (view()->exists($first_view)) {
            return view($first_view, $information);
        } else {
            return view($second_view, $information);
        }
    }
}
