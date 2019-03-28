<?php
$timeStart = microtime(true);

$data = [];

//skapa en koppling till filen och spara i $handle (och misslyckas det så gör inget mer)
if (false !== ($handle = fopen('../data/data_190308.csv', 'r'))) {
    $skip = true;
    //loopa igenom varje rad i filen $handle och dela upp varje rad utifrån tabb, \t
    while (false !== ($row = fgetcsv($handle, 1000, "\t"))) {
        // $data innehåller en array med radens fält uppdelade utifrån tabb
        // viktigt att kolla i filen hur den är separerad, tabb i detta fallet, \t
        // det vanligaste är att separera med ,

        //hantera att första raden innehåller rubriker och hoppa över den
        if ($skip) {
            $skip = false;
            continue;
        }
        
        //gå igenom varje objekt på raden
        $rows = count($row);
        for ($i = 0; $i < $rows; ++$i) {
            // konvertera från UTF-16 till UTF-8
            $converted = mb_convert_encoding($row[$i], 'UTF-8', 'UTF-16');

            //ta bort mellanslag mm i slutet och början av strängen
            $trimmed = trim($converted);
            
            //kolla om det är ett tal och tilldela tillbaka till $data
            if (is_numeric($trimmed)) {
                $row[$i] = floatval($trimmed);
            } else {
                //kolla om det är datum eller vindriktning och sätt till sträng eller nolla annars
                if ($i === 1 || $i === 11) {
                    $row[$i] = $trimmed;
                } else {
                    $row[$i] = 0;
                }
            }
        } //end loop för varje objekt per rad

        //lägg till raden i arrayen som samlar all data
        $data[] = $row;

    } //end loop för att läsa varje rad i filen

    array_pop($data);

    //stäng läsning av filen eftersom loopen är klar
    fclose($handle);
} //end öppna filen


// HÄR SKRIVER NI KODEN FÖR ATT LÄGGA IN I DATABASEN
//var_dump($data);


require "../app/Environment.php";
$env = new Environment();
require "../app/Database.php";
$db = new Database($env);

$rows = $db->storeRows($data);
// var_dump($db);

echo 'Seconds taken: '.(microtime(true)-$timeStart).' for '.$rows.' rows.';