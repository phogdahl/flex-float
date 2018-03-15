<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php

        $addr = gethostbyname("m2m.eclipse.org");
        echo $addr.",";
        $needle = "\x44\x43\x4d\x4c\x30\x32";
        $last_time = time() - 300;
        $message_ID = "";
        $last_level = "";
        $last_whole_buf = "";
        
        // Endless Loop
        while (true)
        {
            if( time() - $last_time >= 300 )
            {   $last_time = time();
                $fp = fsockopen("$addr", 1883, $errno, $errstr, 30);

                socket_set_timeout($fp, 5, 0);

                fwrite ( $fp , "\x10\x11\x00\x04\x4d\x51\x54\x54\x04\x02\x00\x3c"
                    ."\x00\x04\x44\x49\x47\x49\x34", 19 );
                    echo " Conn sent,";

                    $indata = fread($fp, 50);
//                    $indata_hx = bin2hex($indata);
//                    echo "$indata_hx, ";

                    if($indata =="\x20\x02\x00\x00" ) 
                    {
                        echo " Connected,";
                        $connected = 1;
                    }

                fwrite ( $fp , "\x82\x0b\x00\x0a\x00\x06\x44\x43\x4d\x4c\x30"
                    . "\x32\x01", 13 );
                echo " Subscr sent, ";

                $indata = fread($fp, 5);
//                $indata_hx = bin2hex($indata);
//                echo "$indata_hx, ";
                if( $indata == "\x90\x03\x00\x0a\x01" )
                {
                    echo " Got subAck, ";
                }
print <<<END


END;
//                if( time() - $last_time >= 80 )
//                {
//                    fwrite ( $fp , "\xc0\x00", 2 );
//                    echo " Ping sent, ";
//                    $last_time = time();
//                }

                $buf = fread( $fp, 60 );
                if( $buf != "" )
                {
                    $buf_hx = bin2hex($buf);
                    echo "$buf_hx";
print <<<END


END;
                    if( substr( $buf, 0, 4 ) == "\xd0\x00")
                    {
                        echo 'Ping reply received, ';
                    }
                
                    else 
                    {
                        $whole_buf = $buf;
                        $position = strpos ( $buf , $needle );
                        if( $position == FALSE ) 
                        {
                            $topic = "blank";
                            echo "Topic is $topic, ";
                        }
                    
                        else    
                        {
                            $buf = substr( $buf, $position );
                            $topic = substr( $buf, 0, 6 );
                            echo "Topic is $topic, ";
                        }
                
                        $message_ID = substr( $buf, 6, 2 );
                        echo " msg ID is ".bin2hex($message_ID).", ";
                        $single_level = substr( $buf, 8, 2 );
                        $level = substr( $buf, 8, 45 );
                        echo "Level is $level, ";
                        fwrite ( $fp , "\x40\x02$message_ID", 4 );
                        
                        $batvolt = substr( $buf, 53, 2 );
                        echo "Batvolt_str = $batvolt, ";
                        $batvolt = 3.0 + $batvolt / 50;
                        echo "$batvolt";

                
print <<<END


END;
                        if($topic == "DCML02" && $whole_buf !== $last_whole_buf)
                        {    
                            $last_whole_buf = $whole_buf;
                            $mysqli  = new mysqli("localhost", "flexfloatuser1", "SHDTDT", "flex_float");
                            $sql = "INSERT INTO level_history (Level, Batvolt) VALUES ($level, $batvolt)";
                        
                            if ($mysqli->query($sql) === TRUE)
                            {
                                echo "New record created successfully";
                            }
                        
                            else
                            {
                                echo "Error: " . $sql . "<br>";
                            }
print <<<END


END;
                            $mysqli->close();
                        }
                    }
                }
                
//                socket_close($fp);
            }
        }
        ?>
    </body>
</html>
