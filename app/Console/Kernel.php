<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Appointment;
use App\Models\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            \Log::info('Scheduled task is being executed.');

            // Fetch appointments that are scheduled for 3 days from now
            $appointments = Appointment::whereDate('date', now()->addDays(3))->get();

            foreach ($appointments as $appointment) {
                // Check if the notification already exists for this appointment date
                $exists = Notification::where('message', "You have an upcoming appointment with {$appointment->patient->full_name} on {$appointment->date}.")->exists();

                // Only create a new notification if it doesn't exist
                if (!$exists) {
                    Notification::create([
                        'type' => 'appointment',
                        'message' => "You have an upcoming appointment with {$appointment->patient->full_name} on {$appointment->date}.",
                        'status' => 'unread',
                    ]);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
