<?php /* Smarty version 2.3.1, created on 2015-06-11 15:33:00
         compiled from default/login.htm */ ?>
<?php $this->_load_plugins(array(
array('modifier', 'escape', 'default/login.htm', 6, false),)); ?><?php $this->_config_load($this->_tpl_vars['umLanguageFile'], "Login", 'local'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>UebiMiau - <?php echo $this->_run_mod_handler('escape', true, $this->_config[0]['vars']['lgn_title'], "html"); ?>
</title>
	<link rel="stylesheet" href="themes/default/webmail.css" type="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_config[0]['vars']['default_char_set']; ?>
">
</head>

<?php echo $this->_tpl_vars['umJS']; ?>


<body bgcolor="#778899" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<br><br><br>

<table width="400" border="0" cellspacing="0" cellpadding="2" align="center">
	<tr>
		<td><a href="http://uebimiau.sourceforge.net"><img src="images/logo.gif" border=0 alt="Powered by UebiMiau"></a></td>
	<tr>
		<td bgcolor=white>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
			<form name="form1" action="process.php" method=post>
				
				<tr><td align=right class="title" colspan=2>.: <b><?php echo $this->_config[0]['vars']['lgn_welcome_msg']; ?>
</b> :.</td>

				<?php if ($this->_tpl_vars['umServerType'] != "ONE-FOR-EACH"): ?>
				<tr><td align=right class="right" width="30%"><b><?php echo $this->_config[0]['vars']['lng_user_email']; ?>
</b>: &nbsp;</td><td class="default"><input type=text size=10 name="f_email" value="<?php echo $this->_tpl_vars['umEmail']; ?>
" class="textbox" style="width:140px;"></td>
				<?php else: ?>
				<tr><td align=right class="right" width="30%"><b><?php echo $this->_config[0]['vars']['lng_user_name']; ?>
</b>: &nbsp;</td><td class="default"><input type=text size=5 name="f_user" value="<?php echo $this->_tpl_vars['umUser']; ?>
" class="textbox" style="width:80px;"><?php if ($this->_tpl_vars['umAvailableServers'] != 0): ?> <b><?php echo $this->_tpl_vars['umServer']; ?>
</b><?php endif; ?></td>
				<?php endif; ?>
				<tr><td align=right class="right" width="30%"><b><?php echo $this->_config[0]['vars']['lng_user_pwd']; ?>
</b>: &nbsp;</td><td class="default"><input type=password size=5 name="f_pass" value="" class="textbox" style="width:80px;"></td>


				<?php if ($this->_tpl_vars['umAllowSelectLanguage']): ?>
				<tr><td align=right class="right"><b><?php echo $this->_config[0]['vars']['lng_language']; ?>
</b>: &nbsp;</td><td class="default"><?php echo $this->_tpl_vars['umLanguages']; ?>
</td>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['umAllowSelectTheme']): ?>
				<tr><td align=right class="right"><b><?php echo $this->_config[0]['vars']['lng_theme']; ?>
</b>: &nbsp;</td><td class="default"><?php echo $this->_tpl_vars['umThemes']; ?>
</td>
				<?php endif; ?>

				<tr><td class="right">&nbsp;</td><td class="default"><input type=submit name=submit value="<?php echo $this->_config[0]['vars']['lng_login_btn']; ?>
" class="button"></td>
				<tr><td class="cent" colspan="2"><a target="_blank" href="http://uebimiau.sourceforge.net">Powered by UebiMiau!</a></td></tr>
			</form>
			</table>
		</td>
	</tr>
</td>
</table>

</body>
</html>