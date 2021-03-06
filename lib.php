<?php
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

function my_json_decode($line)
{
	// decode a line from graphicsXxx.txt or dictionaryXxx.txt
	$a=new StdClass();
	if (preg_match("/^\\{\"character\":\"([^\"]+)\",\"strokes\":\\[\"([^\\]]+)\"\\],\"medians\":\\[(.+)\\]\\}$/",$line,$match))
	{
		$a->{'character'}=$match[1];
		$a->{'strokes'}=explode("\",\"",$match[2]);
		$x=explode("]],[[",$match[3]);
		$kmx=count($x);
		$x[0]=str_replace("[[","",$x[0]);
		$x[$kmx-1]=str_replace("]]","",$x[$kmx-1]);
		$y=array();
		for($kx=0;$kx<$kmx;$kx++)
		{
			$y=explode("],[",$x[$kx]);
			$kmy=count($y);
			for($ky=0;$ky<$kmy;$ky++)
			{
				$y[$ky]=explode(",",$y[$ky]);
			}
			$x[$kx]=$y;
		}
		$a->{'medians'}=$x;
	}
	else if (preg_match("/\"character\":\"([^\"]+)\"/",$line,$match))
	{
		$a->{'character'}=$match[1];
		if (preg_match("/\"set\":\\[\"([^\\]]+)\"\\]/",$line,$match))
			$a->{'set'}=explode("\",\"",$match[1]);
		if (preg_match("/\"definition\":\"([^\"]+)\"/",$line,$match))
			$a->{'definition'}=$match[1];
		if (preg_match("/\"pinyin\":\\[\"([^\\]]+)\"\\]/",$line,$match))
			$a->{'pinyin'}=explode("\",\"",$match[1]);
		if (preg_match("/\"on\":\\[\"([^\\]]+)\"\\]/",$line,$match))
			$a->{'on'}=explode("\",\"",$match[1]);
		if (preg_match("/\"kun\":\\[\"([^\\]]+)\"\\]/",$line,$match))
			$a->{'kun'}=explode("\",\"",$match[1]);
		if (preg_match("/\"radical\":\"([^\"]+)\"/",$line,$match))
			$a->{'radical'}=$match[1];
		if (preg_match("/\"decomposition\":\"([^\"]+)\"/",$line,$match))
			$a->{'decomposition'}=$match[1];
		if (preg_match("/\"acjk\":\"([^\"]+)\"/",$line,$match))
			$a->{'acjk'}=$match[1];
	}
	return $a;
}

function decUnicode($u)
{
	$len=strlen($u);
	if ($len==0) return 63;
	$r1=ord($u[0]);
	if ($len==1) return $r1;
	$r2=ord($u[1]);
	if ($len==2) return (($r1&31)<< 6)+($r2&63);
	$r3=ord($u[2]);
	if ($len==3) return (($r1&15)<<12)+(($r2&63)<< 6)+($r3&63);
	$r4=ord($u[3]);
	if ($len==4) return (($r1& 7)<<18)+(($r2&63)<<12)+(($r3&63)<<6)+($r4&63);
	return 63;
}

function hexUnicode($u)
{
	return str_pad(dechex(decUnicode($u)),5,"0",STR_PAD_LEFT);
}

function unihanUnicode($u)
{
	return "U+".strtoupper(dechex(decUnicode($u)));
}

function convertJapaneseKun($s)
{
	$s=str_replace("PP","???P",$s);
	$s=str_replace("TT","???T",$s);

	$s=str_replace("AA","A???",$s);
	$s=str_replace("II","I???",$s);
	$s=str_replace("UU","U???",$s);
	$s=str_replace("EE","E???",$s);
	$s=str_replace("OO","O???",$s);

	$s=str_replace("KYA","??????",$s);
	$s=str_replace("KYU","??????",$s);
	$s=str_replace("KYO","??????",$s);

	$s=str_replace("KA","???",$s);
	$s=str_replace("KI","???",$s);
	$s=str_replace("KU","???",$s);
	$s=str_replace("KE","???",$s);
	$s=str_replace("KO","???",$s);

	$s=str_replace("GYA","??????",$s);
	$s=str_replace("GYU","??????",$s);
	$s=str_replace("GYO","??????",$s);
	
	$s=str_replace("GA","???",$s);
	$s=str_replace("GI","???",$s);
	$s=str_replace("GU","???",$s);
	$s=str_replace("GE","???",$s);
	$s=str_replace("GO","???",$s);

	// tsu before su
	$s=str_replace("CHA","??????",$s);
	$s=str_replace("CHU","??????",$s);
	$s=str_replace("CHO","??????",$s);
	
	$s=str_replace("TA","???",$s);
	$s=str_replace("CHI","???",$s);
	$s=str_replace("TSU","???",$s);
	$s=str_replace("TE","???",$s);
	$s=str_replace("TO","???",$s);

	$s=str_replace("SHA","??????",$s);
	$s=str_replace("SHU","??????",$s);
	$s=str_replace("SHO","??????",$s);

	$s=str_replace("SA","???",$s);
	$s=str_replace("SHI","???",$s);
	$s=str_replace("SU","???",$s);
	$s=str_replace("SE","???",$s);
	$s=str_replace("SO","???",$s);

	$s=str_replace("JA","??????",$s);
	$s=str_replace("JU","??????",$s);
	$s=str_replace("JO","??????",$s);
	
	$s=str_replace("ZA","???",$s);
	$s=str_replace("JI","???",$s);
	$s=str_replace("ZU","???",$s);
	$s=str_replace("ZE","???",$s);
	$s=str_replace("ZO","???",$s);

	$s=str_replace("JA","??????",$s);
	$s=str_replace("JU","??????",$s);
	$s=str_replace("JO","??????",$s);

	$s=str_replace("DA","???",$s);
	$s=str_replace("JI","???",$s);
	$s=str_replace("ZU","???",$s);
	$s=str_replace("DE","???",$s);
	$s=str_replace("DO","???",$s);
		
	$s=str_replace("NYA","??????",$s);
	$s=str_replace("NYU","??????",$s);
	$s=str_replace("NYO","??????",$s);
	
	$s=str_replace("NA","???",$s);
	$s=str_replace("NI","???",$s);
	$s=str_replace("NU","???",$s);
	$s=str_replace("NE","???",$s);
	$s=str_replace("NO","???",$s);

	$s=str_replace("HYA","??????",$s);
	$s=str_replace("HYU","??????",$s);
	$s=str_replace("HYO","??????",$s);

	$s=str_replace("HA","???",$s);
	$s=str_replace("HI","???",$s);
	$s=str_replace("FU","???",$s);
	$s=str_replace("HE","???",$s);
	$s=str_replace("HO","???",$s);

	$s=str_replace("BYA","??????",$s);
	$s=str_replace("BYU","??????",$s);
	$s=str_replace("BYO","??????",$s);

	$s=str_replace("BA","???",$s);
	$s=str_replace("BI","???",$s);
	$s=str_replace("BU","???",$s);
	$s=str_replace("BE","???",$s);
	$s=str_replace("BO","???",$s);

	$s=str_replace("PYA","??????",$s);
	$s=str_replace("PYU","??????",$s);
	$s=str_replace("PYO","??????",$s);

	$s=str_replace("PA","???",$s);
	$s=str_replace("PI","???",$s);
	$s=str_replace("PU","???",$s);
	$s=str_replace("PE","???",$s);
	$s=str_replace("PO","???",$s);

	$s=str_replace("MYA","??????",$s);
	$s=str_replace("MYU","??????",$s);
	$s=str_replace("MYO","??????",$s);
	
	$s=str_replace("MA","???",$s);
	$s=str_replace("MI","???",$s);
	$s=str_replace("MU","???",$s);
	$s=str_replace("ME","???",$s);
	$s=str_replace("MO","???",$s);

	$s=str_replace("RYA","??????",$s);
	$s=str_replace("RYU","??????",$s);
	$s=str_replace("RYO","??????",$s);

	$s=str_replace("RA","???",$s);
	$s=str_replace("RI","???",$s);
	$s=str_replace("RU","???",$s);
	$s=str_replace("RE","???",$s);
	$s=str_replace("RO","???",$s);

	// ya after [x]ya
	$s=str_replace("YA","???",$s);
	$s=str_replace("YU","???",$s);
	$s=str_replace("YO","???",$s);
	
	$s=str_replace("WA","???",$s);
	$s=str_replace("WO","???",$s);

	$s=str_replace("A","???",$s);
	$s=str_replace("I","???",$s);
	$s=str_replace("U","???",$s);
	$s=str_replace("E","???",$s);
	$s=str_replace("O","???",$s);
	
	$s=str_replace("N","???",$s);
	
	return $s;
}
function convertJapaneseOn($s)
{
	$s=str_replace("TT","???T",$s);

	$s=str_replace("AA","A???",$s);
	$s=str_replace("II","I???",$s);
	$s=str_replace("UU","U???",$s);
	$s=str_replace("EE","E???",$s);
	$s=str_replace("OO","O???",$s);
	
	$s=str_replace("KYA","??????",$s);
	$s=str_replace("KYU","??????",$s);
	$s=str_replace("KYO","??????",$s);
	$s=str_replace("GYA","??????",$s);
	$s=str_replace("GYU","??????",$s);
	$s=str_replace("GYO","??????",$s);

	$s=str_replace("KA","???",$s);
	$s=str_replace("KI","???",$s);
	$s=str_replace("KU","???",$s);
	$s=str_replace("KE","???",$s);
	$s=str_replace("KO","???",$s);

	$s=str_replace("GA","???",$s);
	$s=str_replace("GI","???",$s);
	$s=str_replace("GU","???",$s);
	$s=str_replace("GE","???",$s);
	$s=str_replace("GO","???",$s);

	// tsu before su
	$s=str_replace("CHA","??????",$s);
	$s=str_replace("CHU","??????",$s);
	$s=str_replace("CHO","??????",$s);

	$s=str_replace("TA","???",$s);
	$s=str_replace("CHI","???",$s);
	$s=str_replace("TSU","???",$s);
	$s=str_replace("TE","???",$s);
	$s=str_replace("TO","???",$s);

	$s=str_replace("SHA","??????",$s);
	$s=str_replace("SHU","??????",$s);
	$s=str_replace("SHO","??????",$s);
	
	$s=str_replace("SA","???",$s);
	$s=str_replace("SHI","???",$s);
	$s=str_replace("SU","???",$s);
	$s=str_replace("SE","???",$s);
	$s=str_replace("SO","???",$s);

	$s=str_replace("JA","??????",$s);
	$s=str_replace("JU","??????",$s);
	$s=str_replace("JO","??????",$s);
	
	$s=str_replace("ZA","???",$s);
	$s=str_replace("JI","???",$s);
	$s=str_replace("ZU","???",$s);
	$s=str_replace("ZE","???",$s);
	$s=str_replace("ZO","???",$s);

	$s=str_replace("CHA","??????",$s);
	$s=str_replace("CHU","??????",$s);
	$s=str_replace("CHO","??????",$s);

	$s=str_replace("TA","???",$s);
	$s=str_replace("CHI","???",$s);
	$s=str_replace("TSU","???",$s);
	$s=str_replace("TE","???",$s);
	$s=str_replace("TO","???",$s);

	$s=str_replace("JA","??????",$s);
	$s=str_replace("JU","??????",$s);
	$s=str_replace("JO","??????",$s);

	$s=str_replace("DA","???",$s);
	$s=str_replace("JI","???",$s);
	$s=str_replace("ZU","???",$s);
	$s=str_replace("DE","???",$s);
	$s=str_replace("DO","???",$s);
		
	$s=str_replace("NYA","??????",$s);
	$s=str_replace("NYU","??????",$s);
	$s=str_replace("NYO","??????",$s);

	$s=str_replace("NA","???",$s);
	$s=str_replace("NI","???",$s);
	$s=str_replace("NU","???",$s);
	$s=str_replace("NE","???",$s);
	$s=str_replace("NO","???",$s);

	$s=str_replace("HYA","??????",$s);
	$s=str_replace("HYU","??????",$s);
	$s=str_replace("HYO","??????",$s);
	
	$s=str_replace("HA","???",$s);
	$s=str_replace("HI","???",$s);
	$s=str_replace("FU","???",$s);
	$s=str_replace("HE","???",$s);
	$s=str_replace("HO","???",$s);

	$s=str_replace("BYA","??????",$s);
	$s=str_replace("BYU","??????",$s);
	$s=str_replace("BYO","??????",$s);

	$s=str_replace("BA","???",$s);
	$s=str_replace("BI","???",$s);
	$s=str_replace("BU","???",$s);
	$s=str_replace("BE","???",$s);
	$s=str_replace("BO","???",$s);

	$s=str_replace("PYA","??????",$s);
	$s=str_replace("PYU","??????",$s);
	$s=str_replace("PYO","??????",$s);

	$s=str_replace("PA","???",$s);
	$s=str_replace("PI","???",$s);
	$s=str_replace("PU","???",$s);
	$s=str_replace("PE","???",$s);
	$s=str_replace("PO","???",$s);

	$s=str_replace("MYA","??????",$s);
	$s=str_replace("MYU","??????",$s);
	$s=str_replace("MYO","??????",$s);

	$s=str_replace("MA","???",$s);
	$s=str_replace("MI","???",$s);
	$s=str_replace("MU","???",$s);
	$s=str_replace("ME","???",$s);
	$s=str_replace("MO","???",$s);

	$s=str_replace("RYA","??????",$s);
	$s=str_replace("RYU","??????",$s);
	$s=str_replace("RYO","??????",$s);

	$s=str_replace("RA","???",$s);
	$s=str_replace("RI","???",$s);
	$s=str_replace("RU","???",$s);
	$s=str_replace("RE","???",$s);
	$s=str_replace("RO","???",$s);

	// ya after [x]ya
	$s=str_replace("YA","???",$s);
	$s=str_replace("YU","???",$s);
	$s=str_replace("YO","???",$s);

	$s=str_replace("WA","???",$s);
	$s=str_replace("WO","???",$s);

	$s=str_replace("A","???",$s);
	$s=str_replace("I","???",$s);
	$s=str_replace("U","???",$s);
	$s=str_replace("E","???",$s);
	$s=str_replace("O","???",$s);

	$s=str_replace("N","???",$s);

	return $s;
}

function convertSet($s,$lang)
{
	if ($lang=="ja")
	{
		if (preg_match("/^g([1-6])$/",$s)) $r=preg_replace("/^g([1-6])$/","Joyo kanji, grade $1",$s);
		else if ($s=="g7") $r="J??y?? kanji, junior high school";
		else if ($s=="g8") $r="Jinmey?? kanji";
		else $r="Uncommon kanji";
	}
	else if ($lang=="zh-Hans")
	{
		if (preg_match("/^hsk([1-6])$/",$s)) $r=preg_replace("/^hsk([1-6])$/","HSK $1",$s);
		else if ($s=="hsk7") $r="Frequent hanzi";
		else if ($s=="hsk8") $r="Common hanzi";
		else $r="Uncommon hanzi";
	}
	else if ($lang=="zh-Hant")
	{
		if (preg_match("/^traditional([1-6])$/",$s)) $r=preg_replace("/^traditional([1-6])$/","HSK $1 traditional",$s);
		else if ($s=="traditional7") $r="Frequent traditional hanzi";
		else if ($s=="traditional8") $r="Common traditional hanzi";
		else $r="Uncommon hanzi";
	}
	else $r="";
	return $r;
}

function getDictionaryData($char,$lang="zh-hans")
{
	$s="<div class=\"dico\">";
	$s.="<div class=\"unicode\"><span class=\"cjkChar\" lang=\"".$lang."\">".$char."</span> ";
	$s.="U+".hexUnicode($char)." "."&amp;#".decUnicode($char).";"."</div>\n";
	if (strtolower($lang)=="ja") $handle=fopen("dictionaryJa.txt","r");
	else if (strtolower($lang)=="zh-hant") $handle=fopen("dictionaryZhHant.txt","r");
	else $handle=fopen("dictionaryZhHans.txt","r");
	if ($handle)
	{
		$k=0;
		while (($line=fgets($handle))!==false)
		{
			$k++;
			if (mb_strpos($line,'{"character":"'.$char,0,'UTF-8')!==false)
			{
				$a=my_json_decode($line);
				if (count($a->{'set'}))
				{
					$s.="<div class=\"set\">";
					$ini=true;
					foreach ($a->{'set'} as $b) {if (!$ini) $s.=", ";$s.=convertSet($b,$lang);$ini=false;}
					$s.="</div>";
				}
				if (property_exists($a,'radical')&&$a->{'radical'})
					$s.="<div class=\"radical\">Radical: <span class=\"cjkChar\" lang=\"".$lang."\">".$a->{'radical'}."</span></div>";
				if (property_exists($a,'decomposition')&&$a->{'decomposition'})
					$s.="<div class=\"radical\">Decomposition: <span class=\"cjkChar\" lang=\"".$lang."\">".$a->{'decomposition'}."</span></div>";
				if (property_exists($a,'acjk')&&$a->{'acjk'})
					$s.="<div class=\"radical\">Acjk: <span class=\"cjkChar\" lang=\"".$lang."\">".$a->{'acjk'}."</span></div>";
				if (($lang=="zh-hans")||($lang=="zh-hant"))
				{
					if (property_exists($a,'pinyin')&&count($a->{'pinyin'}))
					{
						$s.="<div class=\"pinyin\">Pinyin: ";
						$ini=true;
						foreach ($a->{'pinyin'} as $b)
						{
							if (!$ini) $s.=", ";
							$b=str_replace(" ",", ",$b);
							$b=preg_replace("/\\([0-9]+\\)/","",$b);
							$s.=$b;
							$ini=false;
						}
						$s.="</div>";
					}
				}
				else if ($lang=="ja")
				{
					if (property_exists($a,'on')&&count($a->{'on'}))
					{
						$s.="<div class=\"yomi\">Onyomi: ";
						$ini=true;
						foreach ($a->{'on'} as $b)
						{
							if (!$ini) $s.=", ";
							$s.=convertJapaneseOn($b);
							$ini=false;
						}
						$s.="</div>";
					}
					if (property_exists($a,'kun')&&count($a->{'kun'}))
					{
						$s.="<div class=\"yomi\">Kunyomi: ";
						$ini=true;
						foreach ($a->{'kun'} as $b)
						{
							if (!$ini) $s.=", ";
							$s.=convertJapaneseKun($b);
							$ini=false;
						}
						$s.="</div>";
					}
				}
				$s.="<div class=\"english\">Definition: ".$a->{'definition'}."</div>";
				break;
			}
		}
		fclose($handle);
	}
	else $s.="Error";
	$s.="</div>";
	return $s;
}

function transformPathFromGraphics($p)
{
	if (preg_match_all("#([MQCLZ ]+)([0-9.-]+) ([0-9.-]+)#",$p,$m))
	{
		$npm=count($m[0]);
		$q="";
		for ($np=0;$np<$npm;$np++)
		{
			$x=intval($m[2][$np]);
			$y=-(intval($m[3][$np])-900);
			$q.=$m[1][$np].$x." ".$y;
		}
		if (preg_match("/Z/",$p)) $q.="Z";
		return $q;
	}
	return $p;
}

function buildSvg($a)
{
	$u=decUnicode($a->{'character'});
	$id="z".$u;
	$x="xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"";
	$s="<svg id=\"".$id."\" class=\"acjk\" version=\"1.1\" viewBox=\"0 0 1024 1024\" ".$x.">\n";
	
	// style
	$s.="<style>\n<![CDATA[\n";
	$s.="@keyframes zk {\n";
	$s.="\tto {\n";
	$s.="\t\tstroke-dashoffset:0;\n";
	$s.="\t}\n";
	$s.="}\n";
	$s.="svg.acjk path[clip-path] {\n";
	$s.="\t--t:0.8s;\n";
	$s.="\tanimation:zk var(--t) linear forwards var(--d);\n";
	$s.="\tstroke-dasharray:3337;\n"; // more than pathLength + 1
	$s.="\tstroke-dashoffset:3339;\n"; // less than 2 * strokeDasharray - pathLength
	$s.="\tstroke-width:128;\n"; // acjk.strokeWidthMax + 8 or 16?
	$s.="\tstroke-linecap:round;\n";
	$s.="\tfill:none;\n";
	$s.="\tstroke:#000;\n";
	$s.="}\n";
	$s.="svg.acjk path[id] {fill:#ccc;}\n";
	$s.="]]>\n</style>\n";

	// stroke shapes
	$k=0;
	foreach($a->{'strokes'} as $p)
	{
		$k++;
		$p=str_replace(","," ",$p);
		$p=preg_replace("#\s?([MQCLZ])\s?#","$1",$p);
		$p=preg_replace("#([^ ])-#","$1 -",$p);
		// transform coordinates of path nodes (x2 = x1, y2 = 900-y1)
		// don't do this transformation if $_GET["t"] exists and is not 1
		if (!isset($_GET["t"])||($_GET["t"]==1)) $p=transformPathFromGraphics($p);
		$s.="<path id=\"".$id."d".$k."\" d=\"".$p."\"/>\n";
	}
	
	// clip paths
	$s.="<defs>\n";
	$k=0;
	foreach($a->{'strokes'} as $p)
	{
		$k++;
		$s.="\t<clipPath id=\"".$id."c".$k."\">";
		$s.="<use xlink:href=\"#".$id."d".$k."\"/>";
		$s.="</clipPath>\n";
	}
	$s.="</defs>\n";
	
	// medians
	$k=0;
	foreach($a->{'medians'} as $m)
	{
		$k++;
		$z="";
		foreach($m as $point) $z.=($z?"L":"M").$point[0]." ".$point[1];
		if (!isset($_GET["t"])||($_GET["t"]==1)) $z=transformPathFromGraphics($z);
		$s.="<path style=\"--d:".$k."s;\" pathLength=\"3333\" clip-path=\"url(#".$id."c".$k.")\" d=\"".$z."\"/>\n";
	}
	
	$s.="</svg>";
	return $s;
}
?>