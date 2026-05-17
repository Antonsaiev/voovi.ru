<a  href="/dia.php" title="На связи" style ="<?php
        $result = mysql_query("SELECT * FROM `dialog_messages` 
                WHERE (
                    `owner_id` != ".$_COOKIE['id']." and 
                    `view_flag` = 0 and
                    `dialog_id` IN (
                            SELECT `id` 
                            FROM `dialogs` WHERE (
                                    `user1` = ".$_COOKIE['id']." or 
                                    `user2` = ".$_COOKIE['id']."
                            )
                        )
                    )
            ");
        if (mysql_num_rows($result) > 0) {
?>
font-size: 14px;
color: #fff;
background-color:  #3b5998;
display: inline-block;
text-align: center;
            <?php
        }
     ?>" ><span class="glyphicon glyphicon-envelope"></span><span class="badgeeee"><?php 
				$result = mysql_query("SELECT count(*) from dialog_messages WHERE (
                    `owner_id` != ".$_COOKIE['id']." and 
                    `view_flag` = 0 and
                    `dialog_id` IN (
                            SELECT `id` 
                            FROM `dialogs` WHERE (
                                    `user1` = ".$_COOKIE['id']." or 
                                    `user2` = ".$_COOKIE['id']."
                            )
                        )
                    )
            ");
					echo mysql_result($result, 0); 
				?>
			</span></a>
			
			
