<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendAutoNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:send-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Notification Automatic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scheduler jalan jam: ' . now());

        $nowDate = Carbon::now()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i');

        $once = Notification::where('jenis_pengiriman', 'SEKALI')->where('sent', 0)->where('tanggal', $nowDate)->where('jam', $nowTime)->get();

        Work::create([
            'nama' => 'Auto Kirim Notifikasi',
            'zone_id' => 1,
        ]);

        foreach ($once as $key => $notif) {
            $this->sendNotif($notif);

            $notif->update([
                'sent' => 1
            ]);
        }

        $daily = Notification::where('jenis_pengiriman', 'TIAP_HARI')->where('jam', '<=',  $nowTime)->get();

        foreach ($daily as $key => $notif) {
            $this->sendNotif($notif);
        }
    }

    public function sendNotif($notif)
    {
        $users = collect();

        foreach ($notif->activity->ageCategories as $category) {
            $users = $users->merge(
                User::where('role', 'USER')
                    ->where('age_category_id', $category->id)
                    ->pluck('id')
            );
        }

        $users = $users->unique()->values();

        Http::post(url('/api/auto-kirim-notifikasi'), [
            'users' => $users,
            'notification_id' => $notif->id
        ]);

        $this->info('User yang akan dikirim: ' . $users->count());

        return $users;
    }
}
