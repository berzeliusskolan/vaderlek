<?php

//skapa en koppling till filen och spara i $handle (och misslyckas det så gör inget mer)
if (($handle = fopen("data_190308.csv", "r")) !== FALSE) {

    //loopa igenom varje rad i filen $handle och dela upp varje rad utifrån tabb, \t
    while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
        // $data innehåller en array med radens fält uppdelade utifrån tabb
        // viktigt att kolla i filen hur den är separerad, tabb i detta fallet, \t
        // det vanligaste är att separera med ,
        
        $rows = count($data);
    
        //gå igenom varje objekt på raden
        for ($i = 0; $i < $rows; $i++) {
            
            //ta bort skittecken från konstig teckenkodning
            // $data[$i] = mb_convert_encoding(str_replace("\000", "", $data[$i]),"UTF-8");
            // $data[$i] = mb_convert_encoding($data[$i],"UTF-8","UTF-16");
            $str = mb_convert_encoding($data[$i], "UTF-8", "UTF-16");
            var_dump($str);    
            //ta bort mellanslag mm i slutet och början av strängen
            $data[$i] = trim($data[$i]);

            //kolla om värdet INTE går att göra till ett tal för att fånga upp --.- och annat skräp
            if(!is_numeric($data[$i])){

                 //undanta de fält som ska ha strängvärden, som datum och vindriktning
                 if($i!=1 && $i!=11){
                     $data[$i] = 0;
                 }else{
                     $data[$i] = "'" . $data[$i] . "'";
                 }
            }
            

        } //end loop för varje objekt per rad
        
        

        
    } //end loop för att läsa varje rad i filen
    
    //stäng läsning av filen eftersom loopen är klar
    fclose($handle);

} //end öppna filen