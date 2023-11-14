<?php

namespace Erwinrachim\LaravelLokalise\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Lokalise\LokaliseApiClient;
use Spatie\Emoji\Emoji;
use ZipArchive;

class DownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lokalise:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download language from lokalise.com';

    protected $client;
    protected $project_id;
    protected $filter_langs;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new LokaliseApiClient(config('lokalise.api_token'));
        $this->project_id = config('lokalise.project_id');
        $this->filter_langs = config('lokalise.download.filter_langs');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->output->writeln(PHP_EOL);
        $this->info('Downloading language from lokalise.com to local project' . PHP_EOL);

        try {

            $response = $this->client->files->download(
                $this->project_id,
                [
                    'format' => config('lokalise.download.format'),
                    'original_filenames' => config('lokalise.download.original_filenames'),
                    'directory_prefix' => config('lokalise.download.directory_prefix'),
                    'bundle_structure' => config('lokalise.download.bundle_structure'),
                    'filter_langs' => sizeof($this->filter_langs) == 0 ? $this->getLanguageList() : $this->filter_langs,
                    'language_mapping' => config('lokalise.download.langs_mapping'),
                    'replace_breaks' => false,
                ]
            );

            $response = json_decode($response);

            if (isset($response->url)) {
                $remote_file = $response->url;
            } elseif (isset($response->bundle_url)) {
                $remote_file = $response->bundle_url;
            } else {
                $remote_file = false;
            }

            $this->comment('Lokalise file URL: ' . $remote_file . PHP_EOL);
            if ($remote_file) {

                $local_file = storage_path('LocaliseArchive.zip');

                if (File::exists($local_file)) {
                    File::delete($local_file);
                }

                $copy = $this->copyFile($remote_file, $local_file);

                if ($copy) {
                    $this->comment('Download lokalise file ' . Emoji::checkMarkButton());

                    $extract = $this->extractFiles($local_file);
                    if ($extract) {
                        $this->comment('Extract lokalise file ' . Emoji::checkMarkButton());
                    } else {
                        $this->comment('Unable to extract lokalise file ' . Emoji::crossMark());
                    }
                } else {
                    $this->comment('Unable to copy lokalise file ' . Emoji::crossMark());
                }
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }

    /**
     * Copy remote file in local
     *
     * @param [type] $remote_file
     * @param [type] $local_file
     * @return boolean
     */
    private function copyFile($remote_file, $local_file): bool
    {
        try {
            return File::copy($remote_file, $local_file);
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Extract local file
     *
     * @param [type] $local_file
     * @return boolean
     */
    private function extractFiles($local_file): bool
    {

        try {
            $zip = new ZipArchive;

            if ($zip->open($local_file) === true) {
                $zip->extractTo(config('lokalise.language_folder'));
                $zip->close();

                File::delete($local_file);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function getLanguageList(): array
    {

        $languages = [];
        try {
            $response = $this->client->languages->list(
                $this->project_id,
                [
                    'limit' => 200,
                    'page' => 1,
                ]
            );
            $response = json_decode($response);

            if (isset($response->languages)) {
                foreach ($response->languages as $language) {

                    if (config('lokalise.download.skip_en') && ($language->lang_iso == 'en')) {
                        continue;
                    }
                    $languages[] = $language->lang_iso;
                }
            }
        } catch (\Throwable $th) {
        }

        return $languages;
    }
}
