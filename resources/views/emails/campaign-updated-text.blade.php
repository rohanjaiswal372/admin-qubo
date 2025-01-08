Campaign:   {{$campaign->name}}

@if($campaign->status)
Status:     {{$campaign->status->status_id}}
@endif

@if($campaign->approved)Approved By: {{$campaign->approver->fullName}} on the {{Carbon::parse($campaign->status->updated_at)->format('jS \o\f F, Y g:i:s a')}} @endif


Sponsor:    {{($campaign->sponsor)?$campaign->sponsor->name:"No Sponsor"}}

Start Date: {{Carbon::parse($campaign->start_date)->format('m/d/y')}}

End Date:   {{Carbon::parse($campaign->end_date)->format('m/d/y')}}
--------------------------------------------------------------------------------------------------

Message:    {{ strip_tags($campaign->description) }}


This email was also sent to @foreach($campaign->followers as $follower)@if($loop->last)and @endif{{$follower->user->fullname}} <{{$follower->user->email}}>}}@if(!$loop->last), @endif @endforeach

--------------------------------------------------------------------------------------------------

This email is property of ION Media Networks and is not to be used for any other purposes unless written permission is given.
