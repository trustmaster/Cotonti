<!-- BEGIN: MAIN -->

			<div id="left">

				<h1>{PHP.themelang.usersdetails.Sendprivatemessage}</h1>
				<p class="breadcrumb"> {PHP.themelang.list.bread}: <a href="users.php">{PHP.L.Users}</a> {PHP.cfg.separator} <a href="users.php?m=details&amp;id={PHP.usr.id}&amp;u={PHP.usr.name}">{PHP.usr.name}</a> {PHP.cfg.separator} {PMSEND_TITLE} </p>

				<!-- BEGIN: PMSEND_ERROR -->
				<p class="error">{PMSEND_ERROR_BODY}</p>
				<!-- END: PMSEND_ERROR -->
				&nbsp;

				<form action="{PMSEND_FORM_SEND}" method="post">
				<p><strong>{PHP.L.Recipients}</strong></p>
				<p>{PMSEND_FORM_TOUSER}</p>
				<p class="hint"> &nbsp; {PHP.themelang.pmsend.Sendmessagetohint}</p>
				&nbsp;
				<p><strong>{PHP.L.Subject}</strong></p>
				<p class="whitee">{PMSEND_FORM_TITLE}</p>
				&nbsp;
				<p><strong>{PHP.L.Message}</strong></p>
				<div class="pageadd mini"> {PMSEND_FORM_TEXT} <div class="clear"></div> <input type="submit" value="{PHP.L.Submit}" class="submit" /> </div>
				</form>

			</div>

		</div>
	</div>

	<div id="right">
		<h3 class="black">{PHP.themelang.header.logged} {PHP.usr.name}</h3>
		<h3><a href="users.php?m=details&amp;id={PHP.usr.id}&amp;u={PHP.usr.name}">{PHP.L.View} {PHP.L.Profile}</a></h3>
		<h3><a href="users.php?m=profile">{PHP.L.Update} {PHP.L.Profile}</a></h3>
		<h3><span style="background-color:#94af66; color:#fff">{PHP.L.Private_Messages}</span></h3>
		<div class="padding15 admin" style="padding-bottom:0">
			<ul>
				<li>{PMSEND_INBOX}</li>
				<li>{PMSEND_ARCHIVES}</li>
				<li>{PMSEND_SENTBOX}</li>
				<li>{PMSEND_SENDNEWPM}</li>
			</ul>
		</div>
		<h3><a href="pfs.php">{PHP.L.PFS}</a></h3>
		<h3><a href="users.php">{PHP.L.Users}</a></h3>
		&nbsp;
	</div>

	<br class="clear" />

<!-- END: MAIN -->