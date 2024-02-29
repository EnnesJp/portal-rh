<?php

namespace App\Console\Commands;

use App\Models\CompTime;
use App\Models\DayOff;
use App\Models\Punch;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateCompTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comp-time:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $today = '2024-02-26';

            $punches = Punch::all()
                ->where('user_id', $user->id)
                ->where('date', $today)
                ->where('approved', true)
                ->sortBy('time')
                ->values();

            $totalMinutes = 0.0;
            foreach ($punches as $i => $punch) {
                if ($i%2 == 0 && isset($punches[$i + 1])) {
                    $start = new \DateTime($punch->time);
                    $end = new \DateTime($punches[$i + 1]->time);

                    $totalMinutes += $start->diff($end)->h * 60 + $start->diff($end)->i;
                }
            }

            $comp_minutes = $totalMinutes - (8 * 60);
            CompTime::create([
                'user_id' => $user->id,
                'date' => $today,
                'total_minutes' => $totalMinutes,
                'comp_minutes' => $comp_minutes,
            ]);

            $new_comp_minutes = $user->comp_minutes + $comp_minutes;
            User::where('id', $user->id)
                ->update(['comp_minutes' => $new_comp_minutes]);

            $this->info('Comp time updated for ' . $user->name);
        }
    }
}
