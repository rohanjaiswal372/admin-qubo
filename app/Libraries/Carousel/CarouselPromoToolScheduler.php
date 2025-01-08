<?php  namespace App\Libraries\Carousel;

use \Carbon;
use \PromoTool;
use \Cache;
use \App\Carousel;

Class CarouselPromoToolScheduler {

   const DYNAMIC_SLIDE_LIMIT = '5';

   public static function getStatistiques($date = null){

	  $date  = self::formatDate($date);   
	   
	  $date = $date->subDays(1);
	 
       $stats =  \PromoTool::getPromoScheduleStatistiques($date);

	   return $stats;
   }
   
   
   public static function getUpcomingShows($date = null, $limit = self::DYNAMIC_SLIDE_LIMIT ){
	   
	   
	 if($limit == 0) return null;
	 elseif($limit > self::DYNAMIC_SLIDE_LIMIT) $limit = self::DYNAMIC_SLIDE_LIMIT;
	 
	 $date  =  self::formatDate($date);   
	   
	 
	 $stats = array_slice( self::getStatistiques($date) , 0, $limit , true);
	 $shows = [];
	 
	 foreach($stats as $data){
		
		if($data->show &&  $data->show->info){
			
			
			$program = $data->show->info->upcoming_program_from($date->format("Y-m-d"));
			if($program){
				$shows[strtotime($program->airdate)] = $program->show;
				if(count($shows) == $limit) break;				
			}else{
				//echo("program not found for show ".$show->info->name);
			}

		}
	 }
	 	 
	 if(count($shows) == 0){
		 return null;
	 }else{
		 ksort($shows);
		 $shows = array_values($shows);		 
		 $date = $date->addDays(1);		 
		 return (count($shows) < self::DYNAMIC_SLIDE_LIMIT ) ?  array_merge($shows , (array) self::getUpcomingShows( $date , $limit - count($shows) + 1) ) : $shows;		 
	 }

   }
   
   
   public static function getSlides($placement, $date = null){
	   
		$start_date  = self::formatDate($date);   
		$end_date = clone $start_date;
		$end_date = $end_date->addDays(1);
		
	    $custom_slides =  Carousel::get($placement)->getSlides( $start_date , $end_date);
		$upcoming_shows = self::getUpcomingShows( $start_date , self::DYNAMIC_SLIDE_LIMIT);
		$shows = (!empty($upcoming_shows)  && !is_null($upcoming_shows)) ? array_unique($upcoming_shows , SORT_REGULAR) : [];
		
		$dynamic_slides = [];
		if(count($shows)){
			foreach($shows as $key => $show){
				if(!is_null($show->dynamic_slide)) $dynamic_slides[$key + 1] = $show->dynamic_slide;
			}			
		}
		
		$max_custom_slides = count(array_keys($custom_slides)) >= 1 ? max( array_keys($custom_slides)) : 0;
		$max_dynamic_slides = count(array_keys($dynamic_slides)) >= 1 ? max( array_keys($dynamic_slides)) : 0;
		
		$limit = ( $max_custom_slides >= $max_dynamic_slides) ? $max_custom_slides : $max_dynamic_slides;				
		$index = 1;
		$slides = [];
		
		
		for($i = 1; $i <= $limit ; $i++){
			
			if(isset($custom_slides[$i])){
				$custom_slides[$i]->custom = true;
				$slides[$index++] = $custom_slides[$i]; 
			}
			if(isset($dynamic_slides[$i])){
				$slides[$index++] = $dynamic_slides[$i]; 
			}			
		}
	
	    return $slides;
   }
   
   public static function formatDate($date){
	   	 $date  = is_string($date) ? Carbon::parse($date) : ( (!is_null($date) && get_class($date) == "Carbon\Carbon") ? clone $date : Carbon::today());
		 return $date;
   }
   
}