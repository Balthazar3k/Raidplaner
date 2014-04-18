<?php
# Raidplaner Updater
# 2013 by Balthazar3k (GNU)
# auf basis von ilch.de

/*
 * a filename must be (version.sql)
 */

class updater
{
	var $updateSuccess = array();
	var $updateFiles = array();
	var $updateErrors = array();
	var $updatePath = "include/raidplaner/update/";
	
	public function __construct( ){
	
		## Überprüfe auf Updates und Installiere sie! ##
		#..............................................#
		$this->nowUpdate();
		
		## Wenn Updates Vorhanden, dann erfolg Nachrichten ausgeben.##
		#............................................................#
		if( count( $this->updateSuccess ) != 0 ){
			$msg = implode("<br />\n", $this->updateSuccess );
			echo "<div id=\"updateSuccess\">".$msg."</div>";
		}
		
		## Wenn Updates Vorhanden, dann error Nachrichten ausgeben.##
		#...........................................................#
		if( count( $this->updateErrors ) != 0 ){
			$msg = implode("<br />\n", $this->updateErrors );
			echo "<div id=\"updateErrors\">".$msg."</div>";
		}
	}
	
	public function getVersion(){
		return db_result(db_query("SELECT wert FROM prefix_config WHERE schl='version'"), 0);
	}
	
	private function setVersion( $version ){
		return db_query("UPDATE prefix_config SET wert='".escape($version, 'integer')."' WHERE schl='version'");
	}
	
	private function checkVersion(){
		$version = $this->getVersion();
		$dir = scandir( $this->updatePath );
                
		sort( $dir );
		
		foreach( $dir as $k => $file_name ){
			## Ignoriere Ordner Navigation
			$ignore = array(".", "..");
			if( in_array( $file_name, $ignore ) ){
				continue;
			}
                        
			## Überprüfe auf Updates
			if( $version < escape($file_name, 'integer') ){
				$this->updateFiles[escape($file_name, 'integer')] = $this->updatePath . $file_name;
			}else{
				/*if( !@unlink( $this->updatePath . $file_name ) ){
					$this->updateErrors[] = "Update Datei \"". $updateFile ."\" konnte <b>nicht</b> gel&ouml;scht werden!";
				}*/
			}
		}
	}
	
	public function nowUpdate(){
                
		# Überprüfe ob Updates vorhanden sind!
		$this->checkVersion();
                
		foreach( $this->updateFiles as $version => $updateFile ){
			var_dump( $updateFile );
			if( $sql = file_get_contents( $updateFile ) ){
				if( $res = @db_query( $sql ) ){
					## Bei erfolg Setze neue Version in die Config und Logge Nachricht
					$this->setVersion($version);
					$this->updateSuccess[] = "Update ". $version ." wurde erfolgreich aufgespielt!";
					
					## Versuche datei zu Löschen wenn Update ausgeführt wurde!
					if( !@unlink( $this->updatePath . $file_name ) ){
						$this->updateErrors[] = "Update Datei \"". $updateFile ."\" konnte <b>nicht</b> gel&ouml;scht werden!";
					}
				}else{
					$this->updateErrors[] = "Update ". $version ." wurde <b>nicht</b> erfolgreich aufgespielt!";
				}
			}
		}
	}
}
?>