<?php
/*
 * Created on 05.07.2010
 *
 * File created for project TeamServer
 * by nikita
 */
  include('../loading_params.php');
  
  echo $form->create('GameTemplate', array('action' => 'add'));
?>

<table border="0" cellpadding="0" cellspacing="3" width="95%">
	<tr>
		<td align="right">Тип:
		</td>
		<td align="left"><?php echo $form->input('Type.id', 
									array(	'options' => $typesList,
											'selected' => '1',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Краткое название:
		</td>
		<td align="left"><?php echo $form->input('name', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Полное название:
		</td>
		<td align="left"><?php echo $form->input('longname', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Путь к корневой директории  (относительно директории сервера; где находится запускаемый файл сервера):
		</td>
		<td align="left"><?php echo $form->input('rootPath', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Путь к директории addons (относительно директории сервера; для SRCDS серверов):
		</td>
		<td align="left"><?php echo $form->input('addonsPath', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Путь к server.cfg (относительно директории сервера; для SRCDS серверов):
		</td>
		<td align="left"><?php echo $form->input('configPath', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Путь к директории с картами (для игровых серверов):
		</td>
		<td align="left"><?php echo $form->input('mapsPath', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Карта по-умолчанию (для игровых серверов):
		</td>
		<td align="left"><?php echo $form->input('defaultMap', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Расширение файла карт (для игровых серверов):
		</td>
		<td align="left"><?php echo $form->input('mapExt', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Минимум слотов:
		</td>
		<td align="left"><?php echo $form->input('slots_min', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Максимум слотов:
		</td>
		<td align="left"><?php echo $form->input('slots_max', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Слотов по умолчанию:
		</td>
		<td align="left"><?php echo $form->input('slots_value', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Цена за слот:
		</td>
		<td align="left"><?php echo $form->input('price', 
									array(	'size'=> '30',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Цена за приватный слот с паролем:
		</td>
		<td align="left"><?php echo $form->input('pricePrivatePassword', 
									array(	'size'=> '4',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
	<tr>
		<td align="right">Цена за приватный слот с отключением:
		</td>
		<td align="left"><?php echo $form->input('pricePrivatePower', 
									array(	'size'=> '4',
											'div' => false, 
											'label' => false));?>
		</td>
	</tr>
		<tr>
			<td colspan="2" align="center"><?php 
					// Пока неисправят баг в jQuery, будем отсылать обычной кнопкой
					echo $form->submit('Сохранить',
											array('class' => 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only'));


//					echo $js->submit('Сохранить',
//						array(
//							'url'=> array(
//											'controller'=>'GameTemplates',
//											'action'=>'add'
//							 ),
//							'update' => '#templates_list',
//							'class' => 'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
//							'before' =>$loadingShow,
//							'complete'=>"$('#add_template').hide('clip');".$loadingHide,
//							'buffer' => false));
					?>
			
			</td>
	</tr>
</table>

<?php echo $form->end();?>
