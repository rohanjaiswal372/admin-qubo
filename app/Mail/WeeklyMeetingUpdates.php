<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon;
use Auth;
use App\User;

class WeeklyMeetingUpdates extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $starts_today;
    public $starts_this_week;
    public $starts_next_week;
    public $last_week;
    public $action;
    public $followers;
    public $recent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $campaigns,  $action, $followers)
    {
        $this->starts_today = $campaigns['starts_today'];
        $this->starts_this_week = $campaigns['starts_this_week'];
        $this->starts_next_week = $campaigns['starts_next_week'];
        $this->recent = $campaigns['recent'];
        $this->title = $title;
        $this->action = $action;
        $this->followers = $followers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Auth::user();
        if(!$user) $user = User::get('CMichaels');
        return $this->view('emails.weekly-meeting-updates')
            ->from($user->email,$user->fullname)
            ->subject($this->title)
            ->replyTo($user->email,$user->fullname);
    }
}
