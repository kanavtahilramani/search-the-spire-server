<?php
    //The location of the cards
    $locationOfClassCards = 'C:\Users\CCEZa\Downloads\desktop-1.0.jar.src\com\megacrit\cardcrawl\cards';

    //The location of the initial .json
    $locationOfInitialJson = 'E:\Development\cards.txt';
    
    const keyIndex = 0;//Card ID
    const nameIndex = 1;//Card NAME
    const descriptionIndex = 2;//Card DESCRIPTION
    const upgradeDescriptionIndex = 3;//Card UPGRADE

    //find all the paths to each card java file 
    $cardFilePaths = array();
    $di = new RecursiveDirectoryIterator($locationOfClassCards);
    foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            array_push($cardFilePaths,$filename);
    }   
    
    //Split the initial json by '},' to split up each card
    $splitArray = preg_split('/\}\,/',file_get_contents($locationOfInitialJson));

    //The array that will contain all the new card objects
    $cardArray = array();

    foreach($splitArray as $value){
        //Split the information into an array by quotes
        preg_match_all('/\".*\"/',$value,$arrayOfCardText); 
        
        //New card class that will be inserted into the $cardArray
        $card = new stdClass();
        
        //regex pattern to find the NAME of the card
        $pattern = array('/[^a-z\d. ]+/i','/NAME /');
        $card->NAME = preg_replace($pattern,'',$arrayOfCardText[0][nameIndex]);
        if(!isset($card->NAME)){
            throw new Exception('Card Name not found in: '. $value);
        }

        //regex to find the key
        $key = preg_replace('/[^a-z0-9 ._]+/i','',$arrayOfCardText[0][keyIndex]);
        if(!isset($key)){
            throw new Exception('Card ID not found in: '. $value);
        }



        $noFileFlag = true;
        $javaFileString = '';//The java class converted into a string

        //Finds the file that associates with the key in the json
        foreach($cardFilePaths as $filePath){
            if(endsWith($filePath,'.java')){
                $javaFileString = file_get_contents($filePath);
                preg_match('/public static final String ID =.*;/',$javaFileString,$match);
                if(strpos(implode('',$match), $key)){
                    $noFileFlag = false;
                    break;
                    }
            }
        }
        //Error if there is a card in the json without a corresponding java file
        if($noFileFlag == true){
            throw new Exception('No file was found with the card ID: \''. $key .'\'. Please edit the json with correct card ID that matches card java file.');
        }

        //Set the Character trait by checking the file path
        if(preg_match('/green/',$filePath)){
            $card->CHARACTER = 'Green';
        }elseif(preg_match('/red/',$filePath)){
            $card->CHARACTER = 'Red';
        }else{
            $card->CHARACTER = null;
        }

        //Find the cost of the card in the java file string
        if(preg_match('/private static final int COST =.*;/',$javaFileString,$costStringArray)){
            $card->COST = preg_replace('/[^0-9]/', '', implode($costStringArray));
        }else{
            $card->COST = 0;
        }
         
        //Finds the Type of the card
        if(preg_match('/AbstractCard\.CardType\.[A-Za-z]+/',$javaFileString,$cardTypeMatch)){
            $typeArr = explode('.',implode('',$cardTypeMatch));
            $card->TYPE = end($typeArr);
        }
        
        //Finds the Rarity of the card
        if(preg_match('/AbstractCard\.CardRarity\.[A-Za-z]+/',$javaFileString,$cardRarityMatch)){
            $rarityArr = explode('.',implode('',$cardRarityMatch));
            $card->RARITY = end($rarityArr);
        }

        //Description Logic
        //BEGIN
        if(preg_match('/private static final int BLOCK_AMT =.*;/',$javaFileString,$blockAmtStringArray)){
            $BLOCK_AMT = preg_replace('/[^0-9]/', '', implode($blockAmtStringArray));
        }  
        if(preg_match('/private static final int ATTACK_DMG =.*;/',$javaFileString,$attackStringArray)){
            $ATTACK_DMG = preg_replace('/[^0-9]/', '', implode($attackStringArray));
        }
        if(preg_match('/private static final int DEFENSE_GAINED =.*;/',$javaFileString,$defenseGainedStringArray)){
            $DEFENSE_GAINED = preg_replace('/[^0-9]/', '', implode($defenseGainedStringArray));
        }   
        if(preg_match('/this.baseMagicNumber =.*;/',$javaFileString,$magicNumberStringArray)){
            $baseMagicNumber = preg_replace('/[^0-9]/', '', implode($magicNumberStringArray));
        }                      
        if(preg_match('/private static int WEAK =.*;/',$javaFileString,$mv2StringArray)){
            $mv2 = preg_replace('/[^0-9]/', '', implode($mv2StringArray));
        }
        if(preg_match('/private int BASE_STR =.*;/',$javaFileString,$mv3StringArray)){
            $mv3 = preg_replace('/[^0-9]/', '', implode($mv3StringArray));
        }

        $modifiedDescription = preg_replace('/DESCRIPTION\: /','',preg_replace('/\"/','',$arrayOfCardText[0][descriptionIndex]));
        
        if(strpos($modifiedDescription,'!B!')){
            if(!empty($BLOCK_AMT)){
                $modifiedDescription = str_replace('!B!',$BLOCK_AMT,$modifiedDescription); 
            }elseif(!empty($DEFENSE_GAINED)){
                $modifiedDescription = str_replace('!B!',$DEFENSE_GAINED,$modifiedDescription); 
            }  
        }
        if(strpos($modifiedDescription,'!M!')){
            if(!empty($baseMagicNumber)){
                $modifiedDescription = str_replace('!M!',$baseMagicNumber,$modifiedDescription); 
            }elseif(!empty($mv2)){
                $modifiedDescription = str_replace('!M!',$mv2,$modifiedDescription); 
            }elseif(!empty($mv3)){
                $modifiedDescription = str_replace('!M!',$mv3,$modifiedDescription); 
            }
        }
        if(strpos($modifiedDescription,'!D!')){
            if(!empty($ATTACK_DMG)){
                $modifiedDescription = str_replace('!D!',$ATTACK_DMG,$modifiedDescription); 
            }
        }
        $card->DESCRIPTION = str_replace('NL ','',$modifiedDescription);
        
        if(strpos($card->DESCRIPTION,'  ')){
            throw new Exception('Description issues for: '. $key . '. The description is: '. $card->DESCRIPTION);
        }
        //END
        
        //Upgrade description logic
       if(isset($arrayOfCardText[0][upgradeDescriptionIndex])){
            $modifiedUpgradeDescription = preg_replace('/UPGRADE_DESCRIPTION\: /','',preg_replace('/\"/','',$arrayOfCardText[0][upgradeDescriptionIndex]));
       }else{
            $modifiedUpgradeDescription = '';
       }
        
       
        





        $cardArray[$key]=$card;
    }
    file_put_contents('E:\Development\log.txt',json_encode($cardArray));
  


    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
    
        return $length === 0 || 
        (substr($haystack, -$length) === $needle);
    }

    
?>