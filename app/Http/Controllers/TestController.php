<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \Cache;
use \Storage;
use \Config;
use \Tmbd;
use \cURL;
use \Exception;
use \Request;
use \App\Program;
use \App\Show;
use \App\Cast;
use \Brightcove;

class TestController extends Controller {

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
	 
    public function showProfile($id){
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }
	
	public function testBlade(){
		return view("test");
	}
	
	public function testRedis(){
		if (Cache::has('key')) {
			echo("Yes, It is working");
			echo(Cache::get("key"));
		}else{
			Cache::put('key', 'My Value at '.strtotime("now"), 10);
		}
	}
	
	public function testcURL(){
		$url = "https://api.ionpromotool.com/broadview/schedule/ion-television";
		$response = cURL::newRequest('post', $url, ['token' => '6e494f89f15d31ef4c2c1dd438a3ea61'])->send();
		$results = json_decode($response->body);
		
		echo("<pre>");
		var_dump($results);
		echo("</pre>");

	}
	
	public function testBrightcove(){
		
		$v[] =  Brightcove::video("3811918066001");
		$v[] = Program::find(25246986)->episode->preview->brightcove();
		dd($v);
	}
	
	public function testCAPTCHA(){
		return view("captcha");
	}
	
	public function testTMDB(){
	
		$token  = new \Tmdb\ApiToken(Config::get("tmdb")["api_key"]);
		$client = new \Tmdb\Client($token);
		$configuration = null;


		$url = "";
		
		
		if(!Cache::has("tmdb_url")){
			$configuration = $client->getConfigurationApi()->getConfiguration();
			$url = $configuration["images"]["base_url"].$configuration["images"]["poster_sizes"][1];		
			Cache::put('tmdb_url', $url, 60*24 );		
		}else{
			$url = Cache::get("tmdb_url");
		}

		//$movies = array("Ali","Casino Royale","Cinderella Man","Demolition Man","Die Another Day","Goldeneye","Live Free Or Die Hard","Meet The Fockers","Quantum Of Solace","Rescue Dawn","Rocky","Rocky II","Rocky III","Rocky IV","Rocky V","Rudy","The Karate Kid","The Karate Kid, Part II","The Sentinel","The World Is Not Enough","Tomorrow Never Dies","We Are Marshall");
		$movies = array("10,000 BC","12 Wishes of Christmas","21","3-2-1...penguins!","3000 Miles to Graceland","A Bronx Tale","A Christmas Kiss","A Christmas Kiss II","A Christmas Mystery","A Christmas Wedding Date","A Few Good Men","A Girl's Best Friend","A Golden Christmas","A Golden Christmas 2","A Grandpa for Christmas","A Guy Thing","A Holiday Heist","A Knight's Tale","A League Of Their Own","A Man Apart","A Nanny For Christmas","A Perfect Christmas List","A River Runs Through It","A Star For Christmas","A Stranger's Heart","A Time To Kill","A Trace of Danger","A View to a Kill","Above the Law","Ace Ventura: When Nature Calls","Acts of Contrition","Aftershock: Earthquake in New York","Air America","Akeelah And The Bee","Alexander","Ali","Alice in Wonderland","All Dogs Go to Heaven 2","All I Want For Christmas","All the Right Moves","Along Came Polly","American Outlaws","An All Dogs Christmas Carol","Analyze That","Analyze This","Angel Eyes","Another 48 Hours","Anti-Trust","Any Given Sunday","Any Which Way You Can","Anything But Christmas","Archangel","Around the Bend","Assassins","At First Sight","Awakenings","Baby Boom","Baby Mama","Back To Christmas","Back to the Future","Back to the Future II","Back to the Future III","Backdraft","Bad Boys","Bandits","Barbershop","Basic","Batman","Batman & Robin","Batman Forever","Batman Returns","Be Cool","Beauty Shop","Best Friends","Best In Show","Beyond the Call","Black Dog","Blazing Saddles","Blizzard","Blood Diamond","Blood Work","Bloodknot","Bloodsport","Blue Crush","Blue River","Boat Trip","Boyz N' The Hood","Braveheart","Breach","Bringing Out The Dead","Broken Arrow","Bull Durham","Bulletproof","Bulletproof Heart","Caddyshack","Captains Courageous","Captive Heart","Casino","Casino Royale","Chain Reaction","Changing Lanes","Charlie Wilson's War","Cheaper by the Dozen 2","Christmas Belle","Christmas Mail","Christmas Town","Christmas Twister","Cinderella Man","City by the Sea","City Island","City of Angels","City Slickers","City Slickers 2","Clear and Present Danger","Cleopatra","Cobra","Cocktail","Collateral Damage","Colors","Commando","Conan The Barbarian","Conan The Destroyer","Constantine","Cool Hand Luke","Couples Retreat","Courage Under Fire","Cradle 2 The Grave","Crank","Crash","Dances With Wolves","Dante's Peak","Daredevil","Dave","Dead Calm","Deadlocked: Escape from Zone 14","Death Race","Death Wish","Death Wish 4","Death Wish II","Death Wish V","Deep Blue Sea","Defending Santa","Degree of Guilt","Delta Force","Demolition Man","Die Another Day","Die Hard 2","Die Hard: With a Vengeance","Dirty Dancing","Dirty Harry","Disclosure","Divine Secrets of the Ya-Ya Sisterhood","Doctor Dolittle 3","Double Impact","Down Periscope","Dragonheart","Driven","Duplicity","E.T., The Extra-Terrestrial","Elektra","Elopement","End of Days","Entrapment","Eraser","Executive Decision","Exit Wounds","F/x","Falling Down","Farscape: The Peacekeeper Wars","Fast & Furious","Fat Albert","Fatal Error","Field of Dreams","Fighting For My Daughter","Final Approach","Final Days of Planet Earth","First Blood","First Knight","Fly Away Home","Fools Rush In","For Love Of The Game","For Your Eyes Only","Ford: The Man and The Machine","Frequency","Frost/nixon","Gambler V: Playing for Keeps","Get Carter","Get Shorty","Ghostbusters II","Glory","Golden Christmas 3","Goldeneye","Goodfellas","Great Balls Of Fire!","Green Zone","Gridiron Gang","Groundhog Day","Grumpier Old Men","Guarding Tess","Half Past Dead","Hard Target","Hard to Kill","Hart's War","Heartbreak Ridge","Heartbreakers","Heat","Hide and Seek","High Crimes","Hitch","Hogfather","Holiday Road Trip","Home of the Brave","Honeymoon In Vegas","Hoosiers","Human Trafficking","I Now Pronounce You Chuck and Larry","I Spy","I Think I Love My Wife","In Good Company","In Her Shoes","In the Beginning","In the Line of Fire","Incident In A Small Town","Inside Man","Into The Blue","Invasion","Invictus","It's Complicated","Ivana Trump's: For Love Alone","Jack & the Beanstalk","Jane Doe: How to Fire Your Boss","Jane Doe: Til Death Do Us Part","Jarhead","JFK","Joe Dirt","John Q","Journey To The Center Of The Earth","Jumanji","Jumpin' Jack Flash","Jurassic Park III","Just Cause","K-pax","Karate Kid II","Keeping the Faith","Kickboxer","Kindergarten Cop","L.a. Confidential","Ladder 49","Larry McMurtry's Dead Man's Walk (Part 1)","Larry McMurtry's Dead Man's Walk (Part 2)","Larry McMurtry's Dead Man's Walk (Part 3)","LaVyrle Spencer's Home Song","League of Extraordinary Gentlemen","Lean on Me","Leatherheads","Lethal Weapon","LETHAL WEAPON 2","Lethal Weapon 3","Lethal Weapon 4","Lions for Lambs","Live Free Or Die Hard","Lone Rider","Lonesome Dove (Part 1)","Lonesome Dove (Part 2)","Lonesome Dove (Part 3)","Lonesome Dove (Part 4)","Love is a Four Letter Word","Love's Abiding Joy","Love's Long Journey","Love's Unending Legacy","Love's Unfolding Dream","Mad Max Beyond Thunderdome","Magnum Force","Major League","Major League II","Malice","Mama Flora's Family (Part 1)","Mama Flora's Family (Part 2)","Man On Fire","Man On The Moon","Mandie and The Forgotten Christmas","March of the Penguins","Master and Commander: The Far Side of the World","Matchstick Men","Matrix Reloaded","Matrix Revolutions","Maverick","Maximum Risk","McBride: Dogged","McBride: Fallen Idol","McBride: Semper Fi","Meet Joe Black","Meet My Valentine","Meet The Fockers","Meet the Parents","Memphis Belle","Men of Honor","Mercury Rising","Merry Ex-Mas","Miami Vice","Million Dollar Baby","Mission to Mars","Moby Dick","Mother Knows Best","Murder At 1600","Murder In the First","My Dog Skip","My Santa","Mystery Woman: Wild West Murder","Mystic River","National Lampoon's Attack of the 5'2","National Lampoon's European Vacation","National Security","Navy Seals","No Way Out","Noah's Ark","Notting Hill","Nowhere to Run","Nutty Professor II: The Klumps","Ocean's 13","Octopussy","On Deadly Ground","Once Upon a Time in Mexico","One Flew Over the Cuckoo's Nest","One Hour Photo","Out For Justice","Out Of Sight","Out of Time","Outbreak","Overboard","Pacific Heights","Pale Rider","Panic Room","Parenthood","Passenger 57","Patriot Games","Pay it Forward","Paycheck","Philadelphia","Phone Booth","Pink Panther","Pinocchio","Point Break","Prairie Fever","Presumed Innocent","Prey of the Jaguar","Proof of Life","Quantum Of Solace","Rain Man","Ray","Rebound","Red Dawn","Red Dragon","Red Heat","Red Planet","Rescue Dawn","Revenge","Revenge Of The Nerds","Rising Sun","Risky Business","Road House","Rob Roy","Robin Hood","Robocop","Rocky","Rocky II","Rocky III","Rocky IV","Rocky V","Role Models","Romeo Must Die","Rudy","Runaway Jury","Running Scared","Scarface","SCARLETT PART 3 (1994)","SCARLETT PART 4 (1994)","SCORPIO ONE (1997)","Sea People","Search and Rescue","Shadow Of A Doubt","Shanghai Noon","Shapeshifter","Shattered Glass","Silent Night","Silent Predators","Silverado","Sixteen Candles","Sleepers","Slumdog Millionaire","Smokey And The Bandit","Smokey And The Bandit, Pt 2","Snake Eyes","Soldier","Solitary Man","Someone Like You","Something To Talk About","Sophie's Choice","Space Cowboys","Speed 2: Cruise Control","Sphere","Spy Game","Stand by Me","Star Trek IV: The Voyage Home","Starship Troopers","Starsky & Hutch","State Of Play","Stealth","Striking Distance","Sudden Impact","Superman II","Superman III","Superman: The Movie","Supernova (Part 1)","Supernova (Part 2)","Suspect","Swordfish","Tango & Cash","Taxi","Terror in the Family","THE 10TH KINGDOM","The Addams Family","The American President","The Assassination of Jesse James by The Coward Robert Ford","The Bad News Bears","The Bodyguard","The Bone Collector","The Break Up","The Bucket List","The Cable Guy","The Canterville Ghost","The Christmas Clause","The Chronicles Of Riddick","The Clearing","The Client","The Color of Magic","The Cotton Club","The Dead Pool","The Departed","The Diplomat","The Edge","The Elf Who Didn't Believe","The Enforcer","The Family Stone","The Fan","The Fast And The Furious: Tokyo Drift","The Fifth Element","The Forbidden Territory","The Fugitive","The Gauntlet","The Godfather","The Godfather Part II","The Godfather Part III","The Guardian","The Hulk","The Hunt for Red October","The Hurricane","The In-Laws","The Inspectors 2","The Juror","The Karate Kid","The Karate Kid, Part II","The Karate Kid, Part III","The Kingdom","The Last Boy Scout","The Last Templar","The Legend of Sleepy Hollow","The Lincoln Lawyer","The Lucky Ones","The Magical Legend of the Leprechauns","The Majestic","The Man in the Iron Mask","The Mask","The Matrix","The Medallion","The Money Pit","The Mummy: Tomb of the Dragon Emperor","The Natural","The Negotiator","The Next Karate Kid","The Night They Saved Christmas","The Nutty Professor","The Open Road","The Outlaw Josey Wales","The Pathfinder","The Pelican Brief","The Perfect Christmas List","The Perfect Storm","The Pledge","The Principal","The Quick and The Dead","The Rainmaker","The Reading Room","The Replacement Killers","The Replacements","The Return","The Right Stuff","Mad Max 2: The Road Warrior","The Rookie","The Rundown","The Sentinel","The Siege","The Soloist","The Specialist","The Super","The Temptations","The Terminator","Titanic","The Transporter","The Twelve Wishes Of Christmas","The Untouchables","The Usual Suspects","The Visitor","The Whole 10 Yards","The Whole 9 Yards","The Whole Nine Yards","The World Is Not Enough","Thicker Than Water","Three Kings","Tidal Wave: No Escape","Timecop","Tin Cup","To Dance With Olivia","Tomorrow Never Dies","Top Gun","TORNADO (1996)","Training Day","Traitor","Transporter 2","Troy","True Crime","Twisted","Two For The Money","U-571","U.s. Marshals","Unbreakable","Uncle Buck","Uncommon Valor","Under Siege","Under Siege 2: Dark Territory","Undercover Brother","Unforgiven","Unlawful Entry","Uptown Girls","Valkyrie","Vantage Point","Virtuosity","W.","Walking Tall","Wall Street","Waterworld","We Are Marshall","We Own the Night","What Kind of Mother Are You?","When Harry Met Sally","Who's Harry Crumb","Wild Wild West","Wildcats","Windtalkers","Wyatt Earp","You've Got Mail","Young Guns","Young Guns II");
		$chunks = array_chunk($movies, 39 );
		
		echo("<ul style='list-style:none;margin:0px;padding:0px;'>");
		for($i=0;$i<sizeof($chunks);$i++){
		echo("<li style='float:left;margin-left:5px;'><a href='test-tmdb?page={$i}'>{$i}</a></li>");
		}
		echo("</ul><br/><br clear='all'/>");
		
		$page = (Request::has("page") && Request::get("page") >= 0 && Request::get("page") <= sizeof($chunks) -1 ) ? Request::get("page") : 0;
				
		foreach($chunks[$page] as $movie){
			try{
			 

			 $result =  ( Cache::has("tmdb_movies[{$movie}]") ) ? Cache::get("tmdb_movies[{$movie}]") :   $client->getSearchApi()->searchMovies($movie)["results"][0];
			 
			 
			if(!Cache::has("tmdb_movies[{$movie}]")) Cache::put("tmdb_movies[{$movie}]", $result, 60*24 );		

			 echo("<div style='width:160px;height:300px;float:left;border:1px solid darkgrey;'>{$movie}");
		 
			 echo("<br/><img src='".$url.$result["poster_path"]."' /></div>");
			 $results[] = $result;	
			}catch(Exception $e){
				
			}

		}

		echo("<br clear='all'/>");

		dd($results);
	}
	
	public function testTwilio(){
		//https://www.twilio.com/blog/2014/09/getting-started-with-twilio-and-the-laravel-framework-for-php.html
		return "";
	}
	
	public function testRackspace(){
		$disk = Storage::disk("rackspace");
		//$disk->getDriver()->getAdapter()->setPathPrefix("http://iontv.com/");	
		$disk_path  = $disk->getDriver()->getAdapter()->getPathPrefix();
	    $content = $disk->get("logo.gif");
		/*
			$file_extension = "gif";
			switch( $file_extension ) {
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpeg"; break;
				default:
			}

			header('Content-type: ' . $ctype);		
		*/	
		
	   // echo("<br/><pre>");
		echo($content);
		//echo("</pre>");
		

	}	
	
	public function testNitro(){
		
	}
	
	public function testGigya(){
		
	}

}