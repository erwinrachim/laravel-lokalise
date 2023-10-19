<?php

namespace Erwinrachim\LaravelLokalise\Commands;

use Illuminate\Console\Command;
use Lokalise\LokaliseApiClient;
use Illuminate\Support\Facades\File;

class UploadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lokalise:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload local language to lokalise.com';

    protected $client;
    protected $project_id;

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = $this->getFilesList();
        $data = [];

        $this->output->writeln(PHP_EOL);
        $this->info('Uploading local language project to lokalise.com' . PHP_EOL);
        $bar = $this->output->createProgressBar(collect($files)->count());
        foreach ($files as $file) {

            $default_data = [
                'local_iso' => $file['local_iso'],
                'remote_iso' => $file['remote_iso'],
                'filename' => $file['basename'],
            ];

            try {

                if (!File::isDirectory($file['dirname'])) {
                    continue;
                }

                $response = $this->client->files->upload(
                    $this->project_id,
                    [
                        'data' => base64_encode(file_get_contents($file['dirname'] . '/' . $file['basename'])),
                        'filename' => $file['basename'],
                        'lang_iso' => $file['remote_iso'],
                        'slashn_to_linebreak' => true,
                        'distinguish_by_file' => true,
                        'replace_modified' => config('lokalise.upload.replace_modified'),
                    ]
                );

                $response = json_decode($response);

                $data[] = array_merge($default_data, ['response' => '<info>' . $response->process->status . '</info>']);
            } catch (\Exception $e) {
                $data[] = array_merge($default_data, ['response' => '<error>' . $e->getMessage() . '</error>']);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->output->writeln(PHP_EOL);

        $headers = ['Local ISO', 'Remote ISO', 'Filename', 'Status'];
        $this->table($headers, $data);

        $this->output->writeln(PHP_EOL);
    }

    /**
     * Get files list
     *
     * @return array
     */
    private function getFilesList(): array
    {
        $lang_files = [];
        foreach (config('lokalise.upload.languages') as $lang) {

            if (!isset($lang['local_iso'])) {
                continue;
            }

            $language_folder = config('lokalise.language_folder') . '/' . $lang['local_iso'];
            if (File::isDirectory($language_folder)) {
                $files = File::files($language_folder);
                foreach ($files as $file) {
                    $lang_files[] = array_merge($lang, pathinfo($file));
                }
            }
        }

        return $lang_files;
    }
}
