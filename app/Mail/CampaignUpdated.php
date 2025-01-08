<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Campaign;
use Auth;

class CampaignUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $campaign;
    public $action;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, $action)
    {
        $this->campaign = $campaign;
        $this->action = $action;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $action = ($this->action != "")? '('.$this->action.') ' :"";
        if($this->action != "Deleted/Canceled")
            $subject = 'Campaign '.$action.$this->campaign->name.' ['.title_case(str_replace("-"," ",$this->campaign->status->status_id)).']';
        else $subject = $subject = 'Campaign '.$action.$this->campaign->name.' [DELETED]';
        $user = Auth::user();
        return $this->view('emails.campaign-updated')
            ->text('emails.campaign-updated-text')
            ->from($user->email,$user->fullname)
            ->subject($subject)
            ->replyTo($user->email,$user->fullname);
    }
}
