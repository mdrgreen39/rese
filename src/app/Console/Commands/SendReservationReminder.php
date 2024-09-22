<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReservationReminderMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendReservationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reservation-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reservation reminders for reservations happening today';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 今日の予約を取得
        $today = Carbon::today();
        $reservations = Reservation::whereDate('date', $today)->get();

        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminderMail($reservation));
        }

        $this->info('予約リマインダーが送信されました。');
    
    }
}
