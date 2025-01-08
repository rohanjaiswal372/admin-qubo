<?php  namespace App\Libraries\Carousel;

use \Carbon;
use \Cache;
use \App\Carousel;
use \App\Program;

Class CarouselScheduler {

   const DYNAMIC_SLIDE_LIMIT = '5';
   
    public static function getUpcomingShows($date = null, $limit = self::DYNAMIC_SLIDE_LIMIT){
		
		$start_date = !is_null($date) ? Carbon::parse($date)->subMinutes(30) : Carbon::now()->subMinutes(30);
		$end_date = !is_null($date) ? Carbon::parse($date)->addDays(1) : Carbon::now()->addDays(1);
		$programs = Program::whereBetween('airdate',[ $start_date , $end_date ] )->orderBy('airdate','asc')->get();
		$shows = collect();
				
		while( $shows->count() < 5 ){
			
			$program = $programs->shift();

			if(!is_null($program)){
				
				if( !$program->show || is_null($program->show) ) continue;
				
				if( !$shows->contains("id",$program->show->id) ){
					if( $program->show && $program->show->upcoming_program ){
						$program->show->pull_next_air = 1;					
						$program->show->upcoming_program = $program->show->upcoming_program;
						$shows->push($program->show);				
					}
				}else{
					//Show already used, skipping
				}
				
				if($shows->count() == $limit) break;
			}
		}

		$shows->map(function($show){ return $show->dynamic_slide;});

		return  array_slice( $shows->all() , 0 , $limit , true);
		
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
	   	 $date  = is_string($date) ? Carbon::parse($date) : ( (!is_null($date) && get_class($date) == "Carbon\Carbon") ? clone $date : Carbon::now());
		 return $date;
   }
   
}