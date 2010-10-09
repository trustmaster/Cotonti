<!-- BEGIN: MAIN -->

<!-- BEGIN: STANDALONE_HEADER -->
{PFS_STANDALONE_HEADER1}
<link href="themes/{PHP.theme}/css/{PHP.scheme}.css" type="text/css" rel="stylesheet" />
</head>
<body>
<!-- END: STANDALONE_HEADER -->

		<div class="block">
			<h2 class="pfs">{PFS_TITLE}</h2>
			<p class="small">{PFS_SUBTITLE}</p>
			{FILE ./themes/nemesis/warnings.tpl}
			<form id="edit" action="{PFS_ACTION}" method="post">
			<table class="cells">
				<tr>
					<td>{PHP.L.File}:</td>
					<td>{PFS_FILE}</td>
				</tr>
				<tr>
					<td>{PHP.L.Date}:</td>
					<td>{PFS_DATE}</td>
				</tr>
				<tr>
					<td>{PHP.L.Folder}:</td>
					<td>{PFS_FOLDER}</td>
				</tr>
				<tr>
					<td>{PHP.L.URL}:</td>
					<td><a href="{PFS_URL}">{PFS_URL}</a></td>
				</tr>
				<tr>
					<td>{PHP.L.Size}:</td>
					<td>{PFS_SIZE} {PHP.L.kb}</td>
				</tr>
				<tr>
					<td>{PHP.L.Description}:</td>
					<td>{PFS_DESC}</td>
				</tr>
				<tr>
					<td colspan="2" class="valid"><input type="submit" class="submit" value="{PHP.L.Update}" /></td>
				</tr>
			</table>
			</form>
		</div>

<!-- BEGIN: STANDALONE_FOOTER -->
	<div class="block">
		{PHP.R.pfs_icon_pastethumb} {PHP.L.pfs_pastethumb} &nbsp; 
		{PHP.R.pfs_icon_pasteimage} {PHP.L.pfs_pasteimage} &nbsp; 
		{PHP.R.pfs_icon_pastefile} {PHP.L.pfs_pastefile}
	</div>

</body>
</html>
<!-- END: STANDALONE_FOOTER -->

<!-- END: MAIN -->