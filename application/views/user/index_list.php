<div class="first">
	<div class="text" style="text-align:center;">
	<?php echo $pages; ?>&nbsp;
	</div>
</div>
<div class="text_title">
	<div style="float:left;width:25px;">
		&nbsp;
	</div>
	<div style="float:left;width:50%;">
		{title_username}
	</div>
	<div style="float:left;">
		{title_email}
	</div>
</div>
<if name=entry entry>
<list users>
<label for="group_check_{id}">
<div class="{class} list">
<if name=check_{id} check>
	<div style="float:left;width:25px;">
		<input id="group_check_{id}" type="checkbox" value="{id}"{checked} />
	</div>
</if check_{id}>
	<div style="float:left;width:50%;">
		<a href="{module_path}change/{id}">{username}</a>
	</div>
	<div style="float:left;">
		{email}
	</div>
</div>
</label>
</list users>
</if entry>
<else entry>
<div class="first" style="text-align:center;">
	{error_no_entries}
</div>
</else entry>