<?php
    $locationOfClassCards = 'E:\Development\php code';
    $locationOfInitialJson = 'E:\Development\cards.txt';

	
	$cardInformation = file_get_contents($locationOfInitialJson);

    $splitArray = preg_split('/\}\,/',$cardInformation);

    $cardArray = array();

    foreach($splitArray as $value){
       // $value = $splitArray[0];


        $splitProperties = preg_match_all('/\".*\"/',$value,$temp);
        $card = new stdClass();
        $pattern = array('/[^a-z\d. ]+/i','/NAME /');
        $card->NAME = preg_replace($pattern,'',$temp[0][1]);
        $key = preg_replace('/[^a-z0-9 ]+/i','',$temp[0][0]);
        print_r($key);

        $fileNames = scandir($locationOfClassCards);
        foreach($fileNames as $fileName){
            
            $javeFile = file_get_contents($locationOfClassCards."\\".$fileName);
            $flag = preg_match('/public static final String ID =.*;/',$javeFile,$match);
            
            if(strpos(implode('',$match), $key)){
                break;
            }
        }


       if(preg_match('/green\//',$javeFile)){
            $card->CHARACTER = 'Green';
        }elseif(preg_match('/red\//',$javeFile)){
            $card->CHARACTER = 'Red';
        }else{
            $card->CHARACTER = null;
        }

         
         if(preg_match('/private static final int COST =.*;/',$javeFile,$match)){
            $card->COST = preg_replace('/[^0-9]/', '', implode($match));
         }
         $card->COST = 0;

         preg_match('/AbstractCard\.CardType\..*,/',$javeFile,$match);
         $t = 'AbstractCard.CardType.';
         $card->TYPE = substr(implode('',$match),strrpos(implode('',$match),'\.'), strlen(implode('',$match))-strlen($t));

         preg_match('/AbstractCard\.CardRarity\..*,/',$javeFile,$match);
         $t1 = 'AbstractCard.CardRarity.';
         $card->RARITY = substr(implode('',$match),strrpos(implode('',$match),'\.'), strlen(implode('',$match))-strlen($t1));

         $card->DESCRIPTION = preg_replace('/DESCRIPTION\: /','',preg_replace('/\"/','',$temp[0][2]));

         //$card->UPGRADE_DESCRIPTION = preg_replace('/UPGRADE_DESCRIPTION\: /','',preg_replace('/\"/','',$temp[0][3]));;
      

         $fileNames = scandir($locationOfClassCards);
        
        
        $cardArray[$key]=$card;

    }
    file_put_contents('E:\Development\log.txt',json_encode($cardArray));
  




?>