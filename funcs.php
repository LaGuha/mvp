<?
	if (isset($_POST['total'])){
		total();
	}
	if (isset($_GET['industry'])){
		master_table('industry');
	}
	if  (isset($_GET['master'])){
		master_table('master');
	}
	if(isset($_GET['need']) && isset($_GET['kind'])){
      $st = get_news('', $_GET['kind']);
        echo $st;
        //return $st;
    }
	function cutStr($str, $length, $postfix)
	{
		if ( strlen($str) <= $length)
		return $str;

		$temp = substr($str, 0, $length);
		return substr($temp, 0, strrpos($temp, ' ') ) . $postfix;
	}

	function total(){
		include "db.php";
		$level=array();
		$level[]=table( "Premium");
		$level[]=table("I");
		$level[]=table("II");
		$level[]=table("III");
		$level[]=table("IV");
		$level[]=table("V");
		echo json_encode($level);
	}
	function sub(){
		include "db.php";
						$st=$db->query("SELECT * FROM sub");
						$level="Premium уровень прозрачности «Раскрытие информации на уровне лидеров международных практик»";
						?>
							<div id=row>
								<p class="level"><b><?=$level?></b></p>
							</div>
						<?
						while ($company=$st->fetch()){
							if ($company['Type']!=$level){
								$level=$company['Type'];
								?>
									<div id=row>
										<p class="level"><b><?=$level?></b></p>
									</div>
								<?
							}
							?>

								<div id=row>
									<p ><?=$company['N']?></p>
									<p><?=$company['Name']?></p>
									<p ><?=$company['Ind']?></p>
									<p ><?=$company['Score']?></p>
								</div>
							<?
						}
	}
	function subr(){
		include "db.php";
						$st=$db->query("SELECT * FROM subr");
						while ($company=$st->fetch()){
							?>

								<div id=row>
									<p align="center"><?=$company['N']?></p>
									<p><?=$company['Name']?></p>
									<p align="center"><?=$company['Score']?></p>
								</div>
							<?
						}
	}
	function table($level){
		include "db.php";
		$st=$db->prepare("SELECT * FROM total WHERE Level=? ORDER BY Score DESC");
		$st->execute([$level]);
		/*?>
			<div id=row>
				<p class="level"><b><?=$level?> - уровень</b></p>
			</div>
		<?*/
		$mas=array();
		while ($company=$st->fetch()){
			/*if (isset($_SESSION['admin'])){
			?>
				<a href="?id=<?=$company['id']?>#change">
			<?
				}*/
			$mas[]=$company;
			/*?>

			<div id=row>
				<p><?=$company['N']?></p>
				<p><?=$company['Name']?></p>
				<p style="display: flex;align-items: center;">
					<?
						if ($company['warning']!=""){
							?>
								<img src=warning.gif title="<?=$company['warning']?>" width=35 height=35> 
							<?
						}
					?>
					<?=$company['Ind']?>
				</p>
				<p style="display: flex;align-items: center;">
				<?
					if ($company['delta']>0){
						?>
							<img src=up.png width=35 height=35 title="+<?=$company['delta']?>"> 
						<?
						}
						if ($company['delta']<0){
						?>
							<img src=down.png width=35 title="<?=$company['delta']?>" height=35> 
							<?
					}
				?>
					<?=$company['Score']?>
					
				</p>
			</div>
			<?
			if (isset($_SESSION['admin'])){
			?>
				</a>
			<?
				}
		*/}//echo (json_encode($mas));
		return $mas;
	}
	function master_table($param){
		include "db.php";
		if ($param=='industry'){
			$
			$st=$db->prepare("SELECT id, Descr, Level, Score, Kr1, Listing_out, System, Gos, Industry  FROM master_2016 WHERE Industry Like ?");
			$st->execute([$_GET['industry']]);	
		}else{
			$st=$db->query("SELECT id, Descr, Level, Score, Kr1, Listing_out, System, Gos, Industry FROM master_2016");	
		}
		
		
		if ($st->rowCount()==0){
			echo "error";
		}
		$var=array();
		while($company=$st->fetch()){
			$var[]=$company;
		}
		echo(json_encode($var));
	}
	function get_news($link, $kind){
		include "db.php";
        if(is_numeric($kind)){
            $query = htmlentities("SELECT * FROM News WHERE id = ?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($kind));
            $arr = $stmt->fetchAll();
            return json_encode($arr);
        }
        else if($kind == "last three"){
            $query = htmlentities("SELECT * FROM News LIMIT 3");
            $stmt = $db->prepare($query);
            $stmt->execute();
            $arr = $stmt->fetchAll();
            return json_encode($arr);
        }
        else if($kind == "all"){
            $query = htmlentities("SELECT * FROM News");
            $stmt = $db->prepare($query);
            $stmt->execute();
            $arr = $stmt->fetchAll();
            return json_encode($arr);
        }
    }