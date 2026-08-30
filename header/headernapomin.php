<a style=" color: #fff;" href="#"  data-toggle="dropdown" role="button" aria-expanded="false" title="Мои напоминания">
<span class="glyphicon glyphicon-bell"></span>

</a>





				<ul class="dropdown-menu" role="menu">
					<li><a href="/napomin.php">Напоминания <span class="badge-default" data-savoir-count-key="reminders" data-savoir-count-format="plain">...</span></a></li>
				<!--<li><a href="/dia.php">Сообщения <span class="badge-default"><?php 
				/* $result = mysql_query("SELECT count(*) from dialog_messages WHERE (
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
					echo mysql_result($result, 0);  */
				?>
			</span></a></li> -->
				<li><a href="/incident.php">Инцидент</a></li>
				</ul>
