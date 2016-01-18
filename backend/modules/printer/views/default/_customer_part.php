<?php return ''; ?>
<div class="block">
    <div class="leader">Данные пользователя</div>
	<div class="inner">
	    <div class="shopping">
		    <div class="">
			    <table width="50%" style="float: left; border-right:1px solid #9a9a9a; padding-right:20px;">
				    <tbody>
					    <tr>
						    <td class="city">г. <?=$order->deliveryCity?></td>
						</tr>
						<tr>
							<td class="district"><?=$order->deliveryRegion?></td>
						</tr>
						<tr>
							<td class="nova_poshta"><?php if($order['dostavka'] == 1){ ?><span style="font-size: 12px;"><?=$order['dostavkaName']?>:</span><br><?=$order['adress']?><?php ;}else{ if($order['dostavka'] == 6){?>iнТайм<?}?> Cклад №: <?=$order['novaposhta']?><?php ;} ?></td>
						</tr>
						<tr>
							<td class="name"><?=$order['surname']?> <?=$order['name']?></td>
						</tr>
						<tr>
							<td class="telefon"><?=$order['phone']?></td>
						</tr>
						<tr>
							<td class="plateg"><span><span><?=$order['plateg']?></span><img src="/template/img/pole.png"></span></td>
						</tr>
                        <tr>
                            <td class="delivery"><span><span><?=$order['dostavkaName']?></span><img src="/template/img/pole.png"></span></td>
                        </tr>
						<tr>
							<td class="manager"><span><span>Менеджер</span><img src="/template/img/pole.png"></span></td>
						</tr>
						<tr>
							<td class="zakaznumber"><?=$order['id']?></td>
						</tr>
						<?php if($order['text'] != ''){ ?>
						<tr>
							<td class="comment"><?=$order['text']?></td>
						</tr>
						<? } ?>
					</tbody>
				</table>
				<table width="50%" style="float: left; padding-left:20px;">
					<tbody>
						<tr>
							<td class="city">г. <?=$order['city']?></td>
						</tr>
						<tr>
							<td class="district"><?=$order['oblast']?></td>
						</tr>
						<tr>
							<td class="nova_poshta"><?php if($order['dostavka'] == 1){ ?><span style="font-size: 12px;"><?=$order['dostavkaName']?>:</span><br><?=$order['adress']?><?php ;}else{  if($order['dostavka'] == 6){?>iнТайм<?}?> Cклад №: <?=$order['novaposhta']?><?php ;} ?></td>
						</tr>
						<tr>
							<td class="name"><?=$order['surname']?> <?=$order['name']?></td>
						</tr>
						<tr>
							<td class="telefon"><?=$order['phone']?></td>
						</tr>
						<tr>
							<td class="plateg"><span><span><?=$order['plateg']?></span><img src="/template/img/pole.png"></span></td>
						</tr>
                        <tr>
                            <td class="delivery"><span><span><?=$order['dostavkaName']?></span><img src="/template/img/pole.png"></span></td>
                        </tr>
						<tr>
							<td class="manager"><span><span>Менеджер</span><img src="/template/img/pole.png"></span></td>
						</tr>
						<tr>
				    		<td class="zakaznumber"><?=$order['id']?></td>
						</tr>
					</tbody>
				</table>
			    <div class="clear"></div>
			</div>
		</div>
	</div>
</div>