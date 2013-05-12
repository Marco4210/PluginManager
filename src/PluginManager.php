<?php

/*
__PocketMine Plugin__
name=PluginManager
version=1.0.0
author=sekjun9878
class=PluginManager
apiversion=6
*/

class PluginManager implements Plugin{
	private $api;
	
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	
	public function init(){
		   $this->api->console->register("pluginmanager", "PluginManager", array($this, "commandHandler"));
	}
	
	public function __destruct(){
		
	}
	
	public function commandHandler($cmd, $args, $issuer, $alias){
	
		$output = "";
		$cmd = strtolower($cmd);
		
		switch($cmd)
		{
			case "pluginmanager":
				if(!($issuer === "console"))
				break;
			
				$p = strtolower(array_shift($args));
			
				switch($p)
				{
					case "list":
						$output .= "[PluginManager] List of All loaded plugins\n";
					
						foreach($this->api->plugin->getList() as $c)
						{
							$output .= "[PluginManger] \"\x1b[32m".$c['name']."\x1b[0m\" \x1b[35m".$c['version']."\x1b[0m by \x1b[36m".$c['author']."\x1b[0m";
						}
						break;
						
					case "info":
						$plugin_name = array_shift($args);
						$c = $this->api->plugin->getInfo($plugin_name);
						$output .= "[PluginManager] \"\x1b[32m".$c[0]['name']."\x1b[0m\" \x1b[35m".$c[0]['version']."\x1b[0m by \x1b[36m".$c[0]['author']."\x1b[0m\n";
						$output .= "[PluginManager] Compatible with API Versions: \33[1;31m".$c[0]['apiversion']."\x1b[0m\n";
						$output .= "[PluginManager] Methods :\n";
						foreach($c[1] as $m)
						{
							$output .= "[PluginManager] \33[1;33m".$m."\x1b[0m\n";
						}
						$output .= "[PluginManager] End of Info\n";
						break;
						
					case "decodepmf":
						$plugin_name = array_shift($args);
						$output .= "[PluginManager] Decoding PMF plugin ".$plugin_name."\n";
						$bool_code_decoded = false;
						$error_message = "";
						
						$c = $this->api->plugin->getInfo($plugin_name);
						if($c[0]['name'] == $plugin_name)
						{
							$fp = fopen($plugin_name."_Decoded.php", "w");
							if($fp == NULL)
							{
								$bool_code_decoded = false;
								$error_message .= "Could not open new file for storing of decoded pmf\n";
								break;
							}
							if(fwrite($fp, $c[0]['code']) === FALSE)
							{
								$bool_code_decoded = false;
								$error_message .= "Could not write to new file\n";
								break;
							}
							else
							{
								$bool_code_decoded = true;
							}
							
						}
						
						if($bool_code_decoded === true)
						{
							$output .= "[PluginManager] Decoding of PMF plugin ".$plugin_name." Succeeded\n";
							$output .= "[PluginManager] File stored as ".$plugin_name."_Decoded.php in the PocketMine Root directory.\n";
						}
						else
						{
							$output .= "[PluginManager] [Error] Decoding of PMF plugin ".$plugin_name." Failed\n";
							$output .= "[PluginManager] [Error] Error Code: ".$error_message."\n";
						}
						break;
				}
				break;
		}	
		
		return $output;
	}
}
