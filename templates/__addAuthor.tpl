<dl{if $errorField == 'username'} class="formError"{/if}>
	<dt><label for="username">{lang}wcf.acp.article.author{/lang}</label></dt>
	<dd>
		<input type="text" id="username" name="username" value="{$username}" required class="medium" maxlength="255">
		{if $errorField == 'username'}
			<small class="innerError">
				{if $errorType == 'empty'}
					{lang}wcf.global.form.error.empty{/lang}
				{else}
					{lang}wcf.user.username.error.{@$errorType}{/lang}
				{/if}
			</small>
		{/if}
	</dd>
</dl>

<script data-relocate="true">
	require(['WoltLabSuite/Core/Ui/User/Search/Input'], function(UiUserSearchInput) {
		new UiUserSearchInput(elBySel('input[name="username"]'));
	});
</script>