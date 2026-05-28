<?php

	//header('Content-Type: text/html; charset=utf-8');

	@session_name("admin");
	@session_start();

	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	
	if ($_SERVER['HTTP_HOST'] == 'commerce')
	{
		define("db_username", "root");
		define("db_password", "666666");
		define("db_database", "penny_commerce");
	}
	else
	{
		define("db_username", "root");
		define("db_password", "q12we34r!");
		define("db_database", "penny_commerce");
	}
	
	define("db_host", "localhost");
	define("db_codepage", "utf8");
	define("google_api_key", "");

	define('SEQURITY_CHECK',true);

	ini_set('display_errors', true);
	ini_set('display_startup_errors', false);

	/**

	*/

	class db
	{
		private $handle = false;
	
		function db($db_host = false, $db_username = false, $db_password = false, $db_database = false)
		{
			$this->handle = @mysql_connect($db_host ? $db_host : db_host, $db_username ? $db_username : db_username, $db_password ? $db_password : db_password, true);
				
			if ($this->handle)
			{
				mysql_select_db($db_database ? $db_database : db_database, $this->handle);
				$this->query("SET NAMES '".db_codepage."';");
			}
			else {
				say('Could not connect: ' . mysql_error(), true);
			}
		}
		function select($sql)
		{
			$h = $this->query($sql);
			while ($re = @mysql_fetch_assoc($h)) $r[] = $re;
				
			return (count(@$r) > 0) ? $r : false;
		}
		function selectRow($sql)
		{
			$r = $this->select($sql);
		
			return (count($r) > 0) ? $r[0] : false;
		}
		function selectCell($sql)
		{
			$h = $this->query($sql);
			$r = @mysql_fetch_row($h);
				
			return (strlen($r[0]) > 0) ? $r[0] : false;
		}
		function query($sql)
		{
			return mysql_query($sql, $this->handle);
		}

		function selectArr($sql, $_key = '_K', $_value = '_V') /* correct version of selectArr */
		{
			$rows = $this->select($sql);
				
			foreach ($rows as $key=>$value) 
			{
				if (isset($value[$_key]) && (isset($value[$_value]) /*|| is_null($value[$_value])*/)) $out[$value[$_key]] = $value[$_value];
				else if (isset($value[$_key]) && !isset($value[$_value])) $out[$value[$_key]] = $value;
				else if (!isset($value[$_key]) && (isset($value[$_value]) /*|| is_null($value[$_value])*/)) $out[] = $value[$_value];
				else $out[] = $value;
			}
				
			return (count(@$out)) ? $out : array();
		}

		function escape($v) { return escape($v); }
		function getLastID() { return mysql_insert_id($this->handle); }
		function getLatestID() { return mysql_insert_id($this->handle); }
	}

	/**/

	class dbPDO
	{
		private $handle; // handle
		private $debug = true;
		
		function __construct($db_host = false, $db_username = false, $db_password = false, $db_database = false)
		{
			if (!$this->handle)
			{
				$host = $db_host ? $db_host : db_host;
				$host = explode(":", $host);

				if (count($host)>1) $dsn = "mysql:dbname=".($db_database ? $db_database : db_database).";host=".$host[0].";port=".$host[1];
				else $dsn = "mysql:dbname=".($db_database ? $db_database : db_database).";host=".($db_host ? $db_host : db_host);

				try
				{
					$this->handle = new PDO($dsn, ($db_username ? $db_username : db_username), ($db_password ? $db_password : db_password));
					$this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
				catch (PDOException $e) { if ($this->debug) debug("Connection to database failed"); }

				if (is_null($this->handle)) if ($this->debug) debug("Connection failed: {$e->getMessage()}");
				else $this->handle->query("SET NAMES '".db_codepage."';");   
			}
		}
		
		function select($sql)
		{
			try { $out = $this->handle->query($sql)->fetchAll(PDO::FETCH_ASSOC); }
			catch (PDOException $e) { if ($this->debug) debug(array("message" => $e->getMessage(), "function" => __FUNCTION__, "sql" => $sql)); }
				
			$return = (@$out) ? unescape($out) : array();
			return $return;
		}

		function selectRow($sql)
		{
			try { $out = $this->handle->query($sql)->fetchObject(); }
			catch (PDOException $e) { if ($this->debug) debug(array("message" => $e->getMessage(), "function" => __FUNCTION__, "sql" => $sql)); }
				
			$return = (@$out) ? unescape((array)$out) : array();

			return $return;
		}
		
		function selectCell($sql)
		{
			try { $out = $this->handle->query($sql)->fetchColumn(); }
			catch (PDOException $e) { if ($this->debug) debug(array("message" => $e->getMessage(), "function" => __FUNCTION__, "sql" => $sql)); }

			$return = (@$out) ? unescape($out) : false;

			return $return;
		}

		function selectArr($sql, $_key = '_K', $_value = '_V')
		{
			$rows = $this->select($sql);
				
			foreach ($rows as $key=>$value) 
			{
				if (isset($value[$_key]) && (isset($value[$_value]))) $out[$value[$_key]] = $value[$_value];
				else if (isset($value[$_key]) && !isset($value[$_value])) $out[$value[$_key]] = $value;
				else if (!isset($value[$_key]) && (isset($value[$_value]))) $out[] = $value[$_value];
				else $out[] = $value;
			}
				
			return (count(@$out)) ? unescape($out) : array();
		}
		
		function query($sql)
		{
			try { $out = $this->handle->query($sql); }
			catch (PDOException $e) { if ($this->debug) debug(array("message" => $e->getMessage(), "function" => __FUNCTION__, "sql" => $sql)); }
				
			return (@$out) ? true : false;
		}
		
		function escape($v) { return escape($v); }
		function getLastID() { return $this->handle->lastInsertId(); }
		function getLatestID() { return $this->handle->lastInsertId(); }
	}

	/**/

	if (!function_exists('escape'))
	{
		function escape($in)
		{
			if (is_array($in)) foreach ($in as $k=>$v) $in[$k] = escape($v);
			else $in = (!get_magic_quotes_gpc()) ? addslashes(@trim($in)) : @trim($in);

			return $in;
		}
	}

	if (!function_exists('unescape'))
	{
		function unescape($in)
		{
			if (is_array($in)) foreach ($in as $k=>$v) $in[$k] = unescape($v);
			else
			{
				do $in = @trim(stripslashes($in));
				while (strstr($in, '\"'));
			}
				
			return $in;
		}
	}

	if (!function_exists('debug'))
	{
		function debug($str)
		{
			echo "<pre>";
			var_dump($str);
			die;
		}
	}

	if (!function_exists('say'))
	{
		function say($str, $die = false)
		{
			echo date("d.m.Y H:i:s")." # $str\n";
			flush();
			
			if ($die) die;
		}
	}

	if (!function_exists('nameurl'))
	{
		function nameurl($s)
		{
			$s = (string)strip_tags(trim($s));
			$s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
			$s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
			
			/* from js */
			$s = preg_replace("/[&]/i", " and ", $s);
			$s = preg_replace("/[ \^\-\+]/i", "-", $s);			
			$s = preg_replace("/[^A-Za-z0-9._-]/i", "", $s); // \s
			$s = preg_replace("/\s+/i", "-", $s);
			$s = preg_replace("/-+/i", "-", $s);
			
			return $s;
		}
	}

	/**/

	if (!function_exists('mailsendHTML'))
	{
		function mailsendHTML($to_mail, $subj, $text, $from_mail = "noreply@realtor.ru", $from_name = "Penny Lane Robot")
		{
			/*if (strstr($to_mail, "@realtor.ru"))
	        {
	            require_once 'inc/libs/phpmailer/PHPMailerAutoload.php';

	            $mail = new PHPMailer;

	            $mail->isSMTP();
	            $mail->CharSet = 'utf-8';
	            $mail->SMTPDebug = 0; // 0 = off (for production use), 1 = client messages, 2 = client and server messages
	            $mail->Debugoutput = 'html';
	            $mail->Host = "smtp.gmail.com";
	            $mail->Port = 587;
	            $mail->SMTPSecure = 'tls';
	            $mail->SMTPAuth = true;
	            $mail->Username = "noreply.pennylane";
	            $mail->Password = "pennygfhjkm123";
	            $mail->setFrom($from_mail, $from_name);

	            if (is_array($to_mail)) { foreach ($to_mail as $v) $mail->addAddress(trim($v)); }
		        else $mail->addAddress($to_mail);

	            $mail->Subject = $subj;

	            $mail->msgHTML(nl2br(urldecode($text)), dirname(__FILE__));

	            $gms = $mail->send();
	        }
	        else*/
	        {
				require_once 'inc/libs/Mailgun/MailgunApi.php';
		        require_once 'inc/libs/Mailgun/MailgunMessage.php';  

		        $mailgun = new MailgunApi('realtor.ru', 'key-79b7ceefe7b1a0f5fd82127ce9067d3a');
		        $message = $mailgun->newMessage();

		        $message->setFrom($from_mail, $from_name);

		        if (is_array($to_mail)) { foreach ($to_mail as $v) $message->addTo(trim($v)); }
		        else $message->addTo(trim($to_mail));

		        $message->setSubject($subj);

		        $message->setHtml(nl2br(urldecode($text)));

		        $id = $message->send();

		        unset($mailgun, $message);
	        }
		}
	}

	function clearpath($str)
	{
		$str = str_replace("/", DIRECTORY_SEPARATOR, str_replace("\\", DIRECTORY_SEPARATOR, $str));
		$str = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $str);

		return $str;
	}

	function mkdir_recursive($dir, $chmod = 0775, $path = '')
	{
		$dirArray = explode(DIRECTORY_SEPARATOR, clearpath($dir));

		foreach ($dirArray as $one)
		if (strlen($one))
		{
			$path .= $one.DIRECTORY_SEPARATOR;

			if (is_dir($path)) continue;
			
			@mkdir($path, $chmod);
			@chmod($path, $chmod);
		}
	}