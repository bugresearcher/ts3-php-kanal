<?php session_start(); ?>
<?php
	if(!isset($_SESSION['sessionumuz']))
    $_SESSION['sessionumuz'] = microtime(true);
	
	if($_SESSION['sessionumuz'] >= microtime(true))
    die('10 saniye bekleyin flood yapmayin');
	
	$_SESSION['sessionumuz'] = microtime(true)+10.0;
	
	
	if (isset($_POST["olustur"])) {
		
		
		$clib = $_POST['uid'];
		
		$kanaladi = $_POST['kanaladi'];
		$sifre = $_POST['sifre'];
		
		$zaman = time();
		$sure = date('[Y-m-d]-[H:i]',$zaman);
		$devam = "0";
		
		$kafanagore = mt_rand(1, 9999);
		
		$giris = "serverquery://".$kullanici.":".$sifre."@".query.":".$port"/?server_port=".$sunucuportu."";
		$ts3 = TeamSpeak3::factory($giris);
		$ts3->execute("clientupdate", array("client_nickname" => $NICK_QUERY));
		
		$kullanicicek = $ts3->clientGetByUid($clib);
		$idiyicektirbakalim= $kullanicicek[client_unique_identifier];
		$kullanici = 	$kullanicicek[client_nickname];
		
		
		foreach(explode(",", $kullanicicek["client_servergroups"]) as $sgid) 
		{ 
			if($sgid == $idgroup){
				$continue = "1";
			}
			else{
				echo "kanal acma yetkin yok dostum.";
				
			}
			
			break;
		} 
		
		if($devam == "1")
		{
			
			try
			{
				$kanalimiz = $ts3->channelCreate(array(
				"channel_name"          => "[cspacer$rand]$kanaladi", 
				"channel_topic"             => "Kanaldan Olusturuldu", 
				"channel_codec"          => TeamSpeak3::CODEC_SPEEX_ULTRAWIDEBAND, 
				"channel_codec_quality"  => 0x08, 
				"channel_flag_permanent" => TRUE,
				"channel_description" => '[center][b][u]'.$kanaladi.'[/u][/b][/center][hr][b][list][*]tarih: '.$zaman.'[*]Owner: ' . $kullanici . '[/list][/b]',
				"channel_password" => "$sifre", 
				"channel_flag_maxclients_unlimited" => 0, 
				"channel_order"          => $al, 
				)); 	
				
				
				$ilkkanal = $ts3->channelCreate(array(
				"channel_name" => "1. $kanaladi",
				"channel_password" => "$sifre",
				"channel_flag_permanent" => "1",
				"channel_description" => '[center][b][u]'.$kanaladi.'[/u][/b][/center][hr][b][list][*]tarih: '.$zaman.'[*]Owner: ' . $kullanici . '[/list][/b]',
				"cpid"                => $spacertop, 
				));
				
				$digerkanal = $ts3->channelCreate(array(
				"channel_name" => "2. $kanaladi",
				"channel_password" => "$sifre",
				"channel_flag_permanent" => "1",
				"channel_description" => '[center][b][u]'.$kanaladi.'[/u][/b][/center][hr][b][list][*]tarih: '.$zaman.'[*]Owner: ' . $kullanici . '[/list][/b]',
				"cpid"                => $kanalimiz, 
				));
				
				
				$ts3->clientGetByUid($idiyicektirbakalim)->setChannelGroup($ilkkanal, $yoneticikanal);
				$ts3->clientGetByUid($idiyicektirbakalim)->setChannelGroup($digerkanal, $yoneticikanal);
				$ts3->clientMove($kullanicicek, $ilkanal);
				
			}
			catch(Exception $e)
			{
				echo "Error (ID " . $e->getCode() . ") <b>" . $e->getMessage() . "</b>";
			}
			$continue= "0";	
			
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <title>Ts3 Kanal Oluşturucu</title>
     
	</head>
    <body>
        <div class="container">
            <header>
				<h1>Ts3 Kanal<span> Oluşturucu</span></h1>
			</header>
			<section>				
				<div id="container_demo" >
					<div id="wrapper">
						<div id="login" class="animate form">
							<form  method="post" autocomplete="on"> 
								<h1>Ayarlar</h1> 
								
								<p> 
									<label  class="uname" data-icon="u" > Ts3 Kimliğiniz </label>
									<input  name="uid" type="text" placeholder="Ctrl + I Basarak Görürsünüz"/>
								</p>
								<p> 
									<label  class="uname" data-icon="u" > Kanal İsmi </label>
									<input  name="kanaladi" required="required" type="text" placeholder="Örnek: Bugresearcher."/>
								</p>
								<p> 
									<label class="youpasswd" data-icon="p"> Kanal Şifresi</label>
									<input name="sifre" required="required" type="text" placeholder="eg. Bugresearcher" /> 
								</p>
								<p class="login button"> 
									<input type="submit" name="olustur" value="Oluştur!" /> 
								</p>
							</form>
						</div>
						
					</div>
				</div>  
			</section>
			<footer>
			<h2> Oluşturucu <span>Bugresearcher</span> Kullanıcılara Özel <span>Bugresearcher</span>
			</footer>
		</div>
	</body>
</html>	
