<script>
document.getElementById("main_content_header").innerHTML="Add topic";
</script>

<div class="container">
	<form method='post' name="form" action="?controller=forum&action=add_topic" class="form-horizontal">
		<div class="form-group">
			<div class="col-xs-8">
				<label for="topic_name">Topic name:</label>
				<input type="text" name="topic_name" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-8">
				<label for="content">Content:</label><br>
				<textarea id="summernote" name="content" required></textarea>
				  <script>
					$(document).ready(function() {
						$('#summernote').summernote({
						height: 200,
						  toolbar: [
							['style', ['bold', 'italic', 'underline', 'clear']],
							['font', ['superscript', 'subscript']],
							['color', ['color']],
							['para', ['ul', 'ol', 'paragraph']],
						  ]
						});
					});
				  </script>
			</div>
		</div>

		<input type="submit" name="confirm_button" class="btn btn-info" value="Create topic">
	</form>
</div>