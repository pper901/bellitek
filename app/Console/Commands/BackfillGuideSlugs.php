<?php

use Illuminate\Console\Command;
use App\Models\Guide;
use Illuminate\Support\Str;

class BackfillGuideSlugs extends Command
{
    protected $signature = 'guides:backfill-slugs';
    protected $description = 'Generate issue_slug for existing guides';

    public function handle()
    {
        Guide::whereNull('issue_slug')
            ->chunk(100, function ($guides) {
                foreach ($guides as $guide) {
                    $slug = Str::slug($guide->issue);

                    // Handle duplicates safely
                    $original = $slug;
                    $count = 1;

                    while (
                        Guide::where('device', $guide->device)
                            ->where('category', $guide->category)
                            ->where('issue_slug', $slug)
                            ->exists()
                    ) {
                        $slug = $original . '-' . $count++;
                    }

                    $guide->updateQuietly([
                        'issue_slug' => $slug
                    ]);
                }
            });

        $this->info('Guide slugs backfilled successfully.');
    }
}
