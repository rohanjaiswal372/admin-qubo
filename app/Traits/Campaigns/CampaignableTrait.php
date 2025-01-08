<?php namespace App\Traits\Campaigns;

use \Carbon;
use App\AdvertisementCategory;
use App\AdvertisementPlatform;
use App\AdvertisementType;
use App\Campaign;
use App\Sponsor;

trait CampaignableTrait
{
//    use SponsorableLogoTrait,
//        SponsorableBannerTrait,
//        SponsorableThumbnailTrait,
//        SponsorableVideoTrait;

    public function getSelectors()
    {
        $categories = AdvertisementCategory::all();
        $platforms = AdvertisementPlatform::all();
        $types = AdvertisementType::all();
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        $sponsors = Sponsor::orderBy("name")->get();
        return compact('categories', 'campaigns', 'platforms', 'sponsors', 'types');
    }

}
