<?php
require "../vendor/autoload.php";

//definiera konstanter
define('DB_SERVER', 'localhost');
define('DB_USER', 'johkel');
define('DB_PASSWORD', '');
define('DB_NAME', 'c9');

//kopplingsdata, ex mysql
$dsn = 'mysql:dbname='.DB_NAME.';host='.DB_SERVER.';charset=utf8';

//inställningar
$attributes = array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

//skapa anslutningen
$dbm = new PDO($dsn, DB_USER, DB_PASSWORD, $attributes);

//sätt upp variabler vi ska använda
$createTableSQL = "";
$insertSQL = "";

//check if table exists and if not create it
$createTableSQL .= "    DROP TABLE IF EXISTS weather;
            CREATE TABLE weather (
              id int(11) NOT NULL,
              time datetime ,
              intervall int(11) ,
              temp_in float ,
              humidity_in float ,
              temp_out float ,
              humidity_out float ,
              air_pressure_rel float ,
              air_pressure_abs float ,
              wind_speed float ,
              squall float ,
              wind_direction varchar(10) ,
              dew_point float ,
              wind_cooling float ,
              rain_h float ,
              rain_24h float ,
              rain_week float ,
              rain_month float ,
              rain_total float ,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";


//skapa en koppling till filen och spara i $handle (och misslyckas det så gör inget mer)
if (($handle = fopen("WeatherData.csv", "r")) !== FALSE) {

    //börja sql-satsen för insert innan loopen
    $insertSQL = "INSERT INTO weather VALUES\n";

    $firstrow=true;

    //loopa igenom varje rad i filen $handle och dela upp varje rad utifrån tabb, \t
    while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
        // $data innehåller en array med radens fält uppdelade utifrån tabb
        // viktigt att kolla i filen hur den är separerad, tabb i detta fallet, \t
        // det vanligaste är att separera med ,
        
        //hoppa över första raden med rubriker och att sista raden bara innehåller en tom rad med null
        if($firstrow or $data[0]==="\000"){
            $firstrow=false;
            
            //bryt detta varv i loopen och fortsätt på nästa varv
            continue;
        }


        //påbörja varje rads ( )
        $insertSQL .= "(";
        
        //räkna hur många fält det finns i $data, effektivare än att göra det varje gång i loopen
        $rows = count($data);
        
        //gå igenom varje objekt på raden
        for ($i = 0; $i < $rows; $i++) {
            
            //ta bort skittecken från konstig teckenkodning
            $data[$i] = mb_convert_encoding(str_replace("\000", "", $data[$i]),"UTF-8");
            
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
            
            //lägg till det aktuella värdet på insert-strängen
            $insertSQL .= $data[$i] . ",";
            
        } //end loop för varje objekt per rad
        
        //ta bort det extra , som kommer sist eftersom varje varv i loopen lägger till ett
        $insertSQL = rtrim($insertSQL,",");
        
        //avsluta varje rads ( )
        $insertSQL .= "),\n";
        

        
    } //end loop för att läsa varje rad i filen
    
    //stäng läsning av filen eftersom loopen är klar
    fclose($handle);

    //ta bort den sista , och radbrytningar samt lägg på ett ; för att avsluta kommandot
    $insertSQL = rtrim($insertSQL,",\n") . ";";

} //end öppna filen

// sätt samman de två strängarna så att de körs samtidigt
$sql = $createTableSQL . $insertSQL;

//förbered den sammansatta frågan
$stmt = $dbm->prepare($sql);

//kör frågan och spara true/false i $success beroende på om det blev fel eller inte
$success = $stmt->execute();

//om något blir fel så vill vi se exakt vad som blev fel
if($success){
    echo "\nFramgång. Alla queries genomfördes OK.\n";
}else{
    echo "\nPDOStatement::errorInfo():\n";
    !d($stmt->errorInfo());
}