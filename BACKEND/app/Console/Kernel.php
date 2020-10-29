<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\UsersDocument;


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

    protected $middleware = [
        // appending custom middleware
        //'MyApp\Http\Middleware\HttpsProtocol'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //check users_documents for expiration_date less than 30 days
        $schedule->call(function () {

            $userDocuments = UsersDocument::where('status', 'ACTIVE')->get();

            foreach ($userDocuments as $userDocument) {
                if ($userDocument->status == "ACTIVE") {
                    $userDocumentTimeStamp = date(strtotime($userDocument->expiring_date . ' 00:00:00'));
                    $time = time();
                    // $difference = abs($userDocumentTimeStamp - $time) / ( 3600 * 24);
                    // $difference = date_diff($userDocumentTimeStamp,$time);
                    // $date1=date_create("2013-03-15");
                    // $date2=date_create("2013-12-12");
                    // $diff=date_diff($userDocumentTimeStamp,$time);
                    // $difference = $diff->format("%R%a days");
                    // echo $diff->format("%R%a days");

                    // $date1=date_create($userDocument->expiring_date);
                    $date1 = date_create("2020-08-03");
                    $date2 = date_create("2020-08-05");
                    $diff = date_diff($date1, $date2);
                    $difference = $diff->format("%a days");
                    // echo $diff->format("%R%a days");
                    file_put_contents("expiration_emails_log-{$time}.txt", "Active: {$userDocument->status} , Expiration: {$userDocument->expiring_date}, Timestamp : {$userDocumentTimeStamp} , Difference : {$difference}");
                }
            }
        })->everyMinute();
    }
}
