<?php
/*
 * Created on 31.05.2010
 *
 * Made for project TeamServer
 * by bulaev
 */
 $this->layout = 'ajax';
 $vac = array ('1'=>'<span style="color: #668237;">Активен</span>', '0' => 'Отключен');
 //pr($socket);
 include('../loading_params.php');

 function styleMapName( $map = null) {

 	$cleanWords = array('l4d');
 	$changeWord = array('vs' => '(Versus)');
 	$words = split("_", $map);

 	$newMap='';
 	foreach ( $words as $word ) {
    	if (!in_array($word, $cleanWords)) {
    		if (!empty($changeWord[$word])){
    			$newMap .= ' '.$changeWord[$word];
    		}
    		else
    		{
    			$newMap .= ' '.$word;
    		}
    	}
	}

 	if ( $newMap != ''){
 		return ucwords(trim($newMap));
 	}
 	else
 	{
 		return $map;
 	}
 }
?>
<script type="text/javascript">
		function init_<?php echo $serverId; ?> (){
			$('#server_control_<?php echo $serverId; ?>').empty();
			$('#server_control_<?php echo $serverId; ?>').dialog('destroy');
			$('#server_log_view_<?php echo $serverId; ?>').empty();
			$('#server_log_view_<?php echo $serverId; ?>').dialog('destroy');
		}

		function refresh_<?php echo $serverId; ?>(){
			$('#server_control_<?php echo $serverId; ?>').remove();
			$('#server_log_view_<?php echo $serverId; ?>').remove();
		}

		init_<?php echo $serverId; ?>();

</script>

<?php

	$eventRefresh  = $js->request(array('controller'=>'Servers',
							 'action'=>'viewServer', $serverId),
					   array('update' => '#server_more_'.$serverId,
							 'before'=>"init_".$serverId."(); refresh_".$serverId."(); $('#server_more_".$serverId."').showLoading({'addClass': 'loading-indicator-bars'});",
							 'complete'=>"$('#server_more_".$serverId."').hideLoading(); GetStatuses();"));

	$refreshFunc = 'setTimeout(function() { '.$eventRefresh.'; }, 6000 );';
?>

<div style="float: right; margin: 5px; margin-right: 2%;" class="btn-group">

			<?php
	echo $html->link('<i class="icon-repeat"></i> Обновить статус', '#',
					array ('id'=>'server_refresh_'.$serverId, 'escape' => false, 'class' => 'btn'));
	$js->get('#server_refresh_'.$serverId)->event('click', $eventRefresh);

		?>
	<a href="#" onclick="$('#server_more_<?php echo $serverId; ?>').hide('highlight'); GetStatuses(); $('#server_log_view_<?php echo $serverId; ?>').empty(); return false;" class="btn"><i class="icon-remove-sign"></i> Скрыть</a>



</div>
<div id="clear"></div>
<table align="center" style="border: 0px;" width="100%" class="serverFullDesc">
	<tr>
		<td style="width: 200px; text-align: left; padding-left: 15px; padding-top: 5px;" valign="top">
			<?php
				if (!empty($info['Server']['info'])) {

					echo $html->tag('strong',
							@$info['Server']['info']['serverName'],
							array( 'class' => 'highlight3'));

					if (!empty($info['Server']['info']['mapName']))
					{
						echo '<br/>';
						//Иконка для RCON смены карты
						echo $html->link($info['Server']['info']['mapName'].' <i class="icon-share"></i>', '#',
											array('id'=>'rcon_map_'.$serverId,
												  'escape' => false,
												  'title' => "Кликните, чтобы сменить карту на включенном сервере",
												  'style' => ''

											));
						$effect = $js->get('#server_auto_rcon')->effect('slideIn');
						$event  = $js->request(array('controller'=>'servers',
													 'action'=>'setMapRcon', $serverId),
											   array('update' => '#server_auto_rcon',
													 'before'=>$loadingShow,
													 'complete'=>$loadingHide.";$('#server_auto_rcon').dialog({modal: true,position: ['center',80], show: 'clip', hide: 'clip', width: 500, height: 400, title: 'Cменить карту на включенном сервере #".$serverId."'});"
													 ));

						$js->get('#rcon_map_'.$serverId)->event('click', $event);
					}

					?>

					<div id="clear"></div>

					<div style="position: relative;  float: left; margin-top: 0px; padding-left: 0px; text-align: left;">
						<table cellpadding=0 cellspacing=0 align="left">
							<tr>
								<td style="text-align: left;">
									Игроков:<br/>
									Пароль:<br/>
									VAC:<br/>
									Версия:<br/>
								</td>
								<td style="text-align: left;"><?php
									if (!empty($info['Server']['info']))
									{

										$div = $this->Common->getLoadIndicator($info['Server']['info']['playerNumber'], $info['Server']['info']['maxPlayers']);

										echo $html->tag('div','', array('class' => 'players_load '.$div,
																		'style' => 'float: left; margin-top: 3px; margin-right: 5px;'));

										echo "<div style='position: relative; float: left;'>";
										echo @$info['Server']['info']['playerNumber']."/".
										     @$info['Server']['info']['maxPlayers'];

									    if (@$info['Server']['info']['botNumber'] > 0){
									     	echo " (".@$info['Server']['info']['botNumber'].")";
									    }

									    echo "</div>";
									}

									echo "<br/>";
									if (!empty($info['Server']['info']['passwordProtected']))
									{
										echo "Установлен";
									}
									else
									{
										echo "Нет";
									}

									echo "<br/>";
								    echo @$vac[ @$info['Server']['info']['secureServer'] ];

								    echo "<br/>";
								    echo $this->Common->versionStatus(@$currentVersion, @$info['Server']['info']['gameVersion'], 'srcds');
									?>
								</td>
							</tr>
						</table>
					</div>
					<div id="clear"></div>

					<ul id="icons_<?php echo $serverId;?>" style="list-style: none; margin-left: 0px; padding-left: 0px;">
						<li title="Остановить сервер">
									<?php
									//Иконка для остановки сервера
									echo $html->link('<i class="icon-stop"></i> Выключить', '#',
														array( 'id'=>'server_start_stop_'.$serverId,
																'escape' => false,
															    'class'=>"btn",
															    'style' => 'width: 110px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'stop'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";GetStatuses();return false;"
																 ));

									$js->get('#server_start_stop_'.$serverId)->event('click', $event);

									?>
						</li>
						<li title="Перезапустить сервер">
									<?php
									//Иконка для перезапуска сервера
									echo $html->link('<i class="icon-refresh"></i> Перезапуск', '#',
														array('id'=>'server_restart_'.$serverId,
															   'escape' => false,
															   'class'=>"btn",
															    'style' => 'width: 110px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'restart'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"
																 ));

									$js->get('#server_restart_'.$serverId)->event('click', $event);

									?>
						</li>
						<li title="RCON консоль">
									<?php
									//Иконка для RCON консоли
									echo $html->link('<i class="icon-edit"></i> RCON', '#',
														array('id'=>'rcon_'.$serverId,
															  'escape' => false,
															  'class'=>"btn",
															  'style' => 'width: 110px; text-align: left;'

														));
									$effect = $js->get('#server_rcon')->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'rcon', $serverId),
														   array('update' => '#server_rcon',
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";$('#server_rcon').dialog({modal: true,position: ['center',80], show: 'clip', hide: 'clip', width: 700});"
																 ));

									$js->get('#rcon_'.$serverId)->event('click', $event);

									?>
						</li>
						<li  title="Просмотр Лога">
									<?php
									//Иконка для просмотра лога
									echo $html->link('<i class="icon-list-alt"></i> Логи', '#',
														array('id'=>'server_log_'.$serverId,
															  'escape' => false	,
															  'class'=>"btn",
															  'style' => 'width: 110px; text-align: left;'
														));
									$effect = $js->get('#server_log_view_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'viewLog', $serverId),
														   array('update' => '#server_log_view_'.$serverId,
																 'before'=>$loadingShow.";$('#server_log_view_$serverId').empty();",
																 'complete'=>$loadingHide.";$('#server_log_view_$serverId').dialog({modal: true,position: ['center',60], show: 'clip', hide: 'clip', width: 1050});"
																 ));

									$js->get('#server_log_'.$serverId)->event('click', $event);

									?>
						</li>
						<li><br/></li>
						<li title="Обновить сервер">
									<?php
									//Иконка для обновления сервера
									echo $html->link('<i class="icon-retweet"></i> Обновить', '#',
														array('id'=>'server_update_'.$serverId,
															  'escape' => false,
															  'class'=>"btn",
															  'style' => 'width: 110px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'update'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"
																 ));

									$js->get('#server_update_'.$serverId)->event('click', $event);

									?>
						</li>
					</ul>
				<?php
				}
				else
				{
			?>
					<ul id="icons_<?php echo $serverId;?>" style="list-style: none; margin-left: 0px; padding-left: 0px;">
						<li title="Запустить сервер">
									<?php
									//Иконка для запуска сервера
									echo $html->link('<i class="icon-play"></i> Включить', '#',
														array ('id'=>'server_start_stop_'.$serverId,
															   'escape' => false,
															  'class'=>"btn",
															  'style' => 'width: 150px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'start'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"
																 ));

									$js->get('#server_start_stop_'.$serverId)->event('click', $event);

									?>
						</li>
						<li title="Принудительно запустить сервер (в случае ошибок запуска)">
									<?php
									//Иконка для принудительного запуска сервера
									echo $html->link('<i class="icon-step-forward"></i> Принудительно', '#',
														array( 'id'=>'force_server_start_stop_'.$serverId,
															   'escape' => false,
															   'class'=>"btn",
															   'style' => 'width: 150px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'restart'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"
																 ));

									$js->get('#force_server_start_stop_'.$serverId)->event('click', $event);

									?>
						</li>
						<li title="Запустить сервер в режиме отладки">
									<?php
									//Иконка для запуска сервера в режиме отладки
									echo $html->link('<i class="icon-play-circle"></i> Режим отладки', '#',
														array( 'id'=>'debug_server_start_'.$serverId,
															   'escape' => false,
															   'class'=>"btn",
															   'style' => 'width: 150px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'startDebug'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"
																 ));

									$js->get('#debug_server_start_'.$serverId)->event('click', $event);

									?>
						</li>
						<li title="Просмотр Логов сервера">
									<?php
									//Иконка для просмотра лога
									echo $html->link('<i class="icon-list-alt"></i> Логи', '#',
														array('id'=>'server_log_'.$serverId,
															  'escape' => false	,
															  'class'=>"btn",
															  'style' => 'width: 150px; text-align: left;'
														));
									$effect = $js->get('#server_log_view_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'viewLog', $serverId),
														   array('update' => '#server_log_view_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";$('#server_log_view_$serverId').dialog({modal: true,position: ['center',60], show: 'clip', hide: 'clip', width: 1050});"
																 ));

									$js->get('#server_log_'.$serverId)->event('click', $event);

									?>
						</li>
						<li><br/></li>
						<li title="Обновить сервер">
									<?php
									//Иконка для обновления сервера
									echo $html->link('<i class="icon-retweet"></i> Обновить', '#',
														array('id'=>'server_update_'.$serverId,
															  'escape' => false,
															  'class'=>"btn",
															  'style' => 'width: 150px; text-align: left;'

														));
									$effect = $js->get('#server_control_'.$serverId)->effect('slideIn');
									$event  = $js->request(array('controller'=>'servers',
																 'action'=>'script', $serverId,'update'),
														   array('update' => '#journal_'.$serverId,
																 'before'=>$loadingShow,
																 'complete'=>$loadingHide.";"));

									$js->get('#server_update_'.$serverId)->event('click', $event);

									?>
						</li>
					</ul>
			<?php

				}

			?>
		</td>
		<td style="" valign="top">
		<?php
			// Текущее состояние сервера, если включён

			// Список игроков
			if (!empty($info['Server']['info']))
				{
			?>
					<div style="position: relative; float: left; vertical-align:bottom; margin-top: 5px; margin-right: 5px;">
						<?php
							if (!empty($mapDesc['image'])){
								echo $html->image('gameMaps/'.$mapDesc['image'].'.jpg', array('title'  => @$mapDesc['longname'],
																									'width'  => 320,
																									'height' => 240));
							}


						?>
					</div>

			<?php
					if (!empty($info['Server']['info']['playerNumber'])){

						$i=1;
						$playerForList = '';
						foreach ( $info['Server']['players'] as $player ) {

			       			$playerForList .= "<tr>";
			       			$playerForList .= "<td>".$i++."</td>";
			       			$playerForList .= '<td style="text-align: left;">';
			       			$playerForList .= $this->Text->truncate(
																    htmlspecialchars($player->getname()),
																    25,
																    array(
																        'ending' => '*',
																        'exact' => true
																    )
																);
							$playerForList .= "</td>";
							$playerForList .= '<td align="center">';

							if ($player->getscore() > 100000){
			       						$playerForList .= '0';
			       					}
			       					else
			       					{
			       						$playerForList .= $player->getscore();
			       					}
			       			$playerForList .= "</td>";

							$playerForList .= '<td align="center">';

							$secondsFull = intval($player->getconnectTime());
											$minutesFull = intval($secondsFull/60);
											$hours = intval($minutesFull/60);
											$minutes = $minutesFull - $hours*60;
											$seconds = $secondsFull - $minutes*60;

							$playerForList .= sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);;

							$playerForList .= "</td>";
							$playerForList .= "</tr>\n";

						}

						$playersList = "

							<table cellpadding=4 cellspacing=0 border=0 class=score>
								<tr>
				       				<th>#</th>
				       				<th align=left>Игрок</th>
				       				<th>Счёт</th>
				       				<th>Время</th>
				       			</tr>

				       			$playerForList

				       		</table>
						";
					?>
					<div style="float: left; position: relative; margin-top: 5px; margin-left: 5px; margin-right: 0px; max-width: 305px; min-width: 276px;">
					<?php
					echo $playersList;
					?>
					</div>
					<?php

					}
					else
					{
						echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="float: left; padding: 8px; margin: 0px auto; margin-top: 5px; margin-left: 5px; min-width: 276px; max-width: 302px;">'."\n";
						echo  "На сервере нет игроков.";
						echo "</div>";
					}

				}

			else
			{
			// Текущее состояние сервера, если выключен
			if (!empty($status['status'])){
				echo '<span style="text-align: left;">';

				switch ( $status['status'] ) {
						case "exec_error":
							echo '<div class="ui-state-error ui-corner-all" style="padding: 8px; margin: 5px;">' .
								 '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> ';
							echo "Ошибка запуска. <br/>" .
								 "Попробуйте перезапустить сервер. <br/>" .
								 "Если не поможет - обратитесь в техподдержку.";

							if (@$status['statusDescription']){
								echo "<br/>Причина: ".$status['statusDescription'];
							}
						    echo "<br/> <small>Время статуса: ".$this->Common->niceDate($status['statusTime'])."</small>";
						    echo "</div>";
							break;

						case "exec_success":
							if ($time->wasWithinLast('5 minutes', $status['statusTime'])){
								echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px;">'."\n";
								echo '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'."\n";
								echo "Сервер запущен менее 5 минут назад. <br/>" .
									 "Если сервер требует обновления, процесс запуска может затянуться. " .
									 "Читайте соответсвующий лог.";
								echo "</div>";
							}
							else
							{
								echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px;">'."\n";
								echo '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'."\n";
								echo "Сервер был запущен более 5 минут назад, но до сих пор не удаётся " .
									 "прочесть его статус.<br/>" .
									 "Если вы не включали читы параметром 'sv_cheats 1', то возможно, " .
									 "что сервер обновляется. В этом случае процесс запуска может затянуться. " .
									 "Читайте соответсвующий лог. <br/>" .
									 "Также вероятной проблемой может быть то, что вы внесли IP вашего сервера в бан-лист. " .
									 "Проверьте banned_ip.cfg на наличие в нем IP-адреса вашего сервера.<br/>" .
									 "Если в логе нет данных, либо есть ошибки, " .
									 "попробуйте перезапустить сервер. <br/>" .
									 "Если не поможет - обратитесь в техподдержку.";
								echo "<br/> <small>Время статуса: ".$this->Common->niceDate($status['statusTime'])."</small>";
								echo "</div>";
							}

							break;

						case "update_error":
							echo '<div class="ui-state-error ui-corner-all" style="padding: 8px; margin: 5px;">' .
								 '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> ';
							echo "Ошибка обновления сервера. <br/>" .
								 "Попробуйте повторить обновление. <br/>" .
								 "Если не поможет - обратитесь в техподдержку.";
							echo "<br/> <small>Время статуса: ".$this->Common->niceDate($status['statusTime'])."</small>";
							echo "</div>";
							break;

						case "update_started":
							echo $this->element('action_progress', array('id' => $serverId));
							echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px;">'."\n";
							echo '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'."\n";
							echo "Запущено обновление сервера. <br/>" .
								 "О состоянии обновления вы можете прочитать в соответсвующем логе.<br/>" .
								 "После завершения обновления вам необходимо запустить сервер вручную и " .
								 "это сообщение исчезнет.";
							echo "<br/> <small>Время статуса: ".$this->Common->niceDate($status['statusTime'])."</small>";
							echo "</div>";

							echo $this->Html->scriptStart();
							echo "$('#progressBar_$serverId').showStatusTs($serverId, 'update');";
							echo $this->Html->scriptEnd();
							break;

						case "stoped":
						case "stopped":
							echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px;">'."\n";
							echo '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'."\n";
							echo "Сервер выключен ".$this->Common->niceDate($status['statusTime']).". ";
							if (@$status['statusDescription']){
								echo "Причина: ".$status['statusDescription'];
							}
							echo "</div>";
							break;

						default:
							echo "Ошибка подключения. Сервер выключен?";
							break;
					}

			echo "</span>";
			}
			else
			{
				echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px; margin-top: 10px; text-align: left;">'."\n";
				echo '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'."\n";
				echo "Ошибка подключения. Сервер еще ни разу не включался?";
				echo "</div>";

			}

			echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px; margin-top: 10px; text-align: left;">'."\n";
			echo '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>'."\n";
			echo "<i>Подсказка: При ошибках включения сервера используйте принудительный запуск. " .
				 "Будут проведены дополнительные тесты и очистка от прошлых неудачных включений.</i> ";
			echo "</div>";

			echo '<div class="ui-state-highlight ui-helper-clearfix ui-corner-all" style="padding: 8px; margin: 5px; margin-top: 10px; text-align: left;">'."\n";
			echo '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>'."\n";
			echo "<i>Подсказка: При ошибках в работе сервера: падения, перезагрузки, подвисания и т.д., " .
				 "запустите сервер в режиме Отладки. Будет писаться подробный лог работы сервера в один файл,  " .
				 "из которого чаще всего удаётся понять и решить проблему.</i>";
			echo "</div>";
		?>

		<?php
			}
		?>

		</td>
		<td valign="top" style="width: 266px;">

			<?php echo $this->element('graphs', array( 'graphs'=>$graphs )); ?>

		</td>
	</tr>
</table>

<div id="server_control_<?php echo $serverId;?>" style="display:none" title="Управление сервером #<?php echo $serverId;?>"></div>
<div id="server_log_view_<?php echo $serverId;?>" style="display:none" title="Просмотр лога сервера #<?php echo $serverId;?>"></div>

<?php
			echo $js->writeBuffer(); // Write cached scripts
?>
