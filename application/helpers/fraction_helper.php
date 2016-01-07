<?php

	//Replace decimals with fractions
    function fraction($num)
	{              
			$decimal = 0;
			$fraction = NULL;
			$num_explode = explode('.',$num);
			
			if (isset($num_explode['1']))
			{
				$fraction = $num_explode['1'];
			}
			
                        
			if ( $fraction ) {
				switch ($fraction) {
				 
                case "5":
				$decimal = "&frac12;";
				break;
                                
                case "501":
				$decimal = "&frac12;";
				break;
					
				case "502":
				$decimal = "&frac12;";
				break;

				case "25":
				$decimal = "&frac14;";
				break;
				case "75":
				$decimal = "&frac34;";
				break;
				case "167":
				$decimal = "&frac16;";
				break;
				case "169":
				$decimal = "&frac16;";
				break;
				
				case "67":
				$decimal = "&frac16;";
				break;
				
				case "334":
				$decimal = "&frac13;";
				break;
                            
                            
                        
				
                                case "835":
				$decimal = "&frac56;";
                                    
				break;
                            
                                 case "834":
				$decimal = "&frac56;";
                                    
				break;
                                
                                case "667":
				$decimal = "&frac23;";
				break;
                            
				case "668":
				$decimal = "&frac23;";
				break;
                            
				default:
				//$decimal = ".".$fraction;
				$decimal = "";
				break;
				}
			}
			
			if ($num_explode["0"] == 0) {
				return $decimal;
			}
			else
			{
				
				return $num_explode["0"].",".$decimal;
				//return $decimal;
			}
	}