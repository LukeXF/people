<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
<style>
	.alert {
		margin-top: 2px;
		margin-bottom: 2px;
		padding: 5px 15px;
		font-size: 12px;
		border-radius: 0;
	}

	.jumbotron {
		background: #333;
	}

	.title {
		color: #fff !important;
		font-weight: 100;
		text-align: center;
	}

	* {
		font-family: 'Lato', sans-serif;
		font-weight: 300;
	}

	.checkbox {
		background: white;
		padding: 10px 20px;
	}
</style>
<script>
	$(document).ready(function () {


		$("#tags").click(function () {
			if ($('#tags').is(':checked')) {
				console.log("checked");
				$(".colLevel-1").hide();
			} else {
				console.log("not checked");
				$(".colLevel-1").show();
			}
		});
		var unsuccessfulUsers = $('.level-0.alert-warning').length;
		$('#unsuccessful-users').html(unsuccessfulUsers);

		var successfulUsers = $('.level-0.alert-success').length;
		$('#successful-users').html(successfulUsers);

		var unsuccessfulTags = $('.level-1.alert-warning').length;
		$('#unsuccessful-tags').html(unsuccessfulTags);

		var successfulTags = $('.level-1.alert-success').length;
		$('#successful-tags').html(successfulTags);

		var seconds = $('#seconds').html();
		$('#load-time').html(seconds + " seconds");

		console.log("ready!");
	});

</script>
<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<h1 class="title">PHP Import Script for people.json</h1>
				<div class="col-md-2 col-xs-6">
					<div class="alert alert-success">
						<h2 id="successful-users">-</h2>
						Users Imported
					</div>
				</div>
				<div class="col-md-2 col-xs-6">
					<div class="alert alert-danger">
						<h2 id="unsuccessful-users">-</h2>
						Failed Users
					</div>
				</div>
				<div class="col-md-2 col-xs-6">
					<div class="alert alert-success">
						<h2 id="successful-tags">-</h2>
						Tags Imported
					</div>
				</div>
				<div class="col-md-2 col-xs-6">
					<div class="alert alert-danger">
						<h2 id="unsuccessful-tags">-</h2>
						Failed Tags
					</div>
				</div>
				<div class="col-md-4 col-xs-12">
					<div class="alert alert-info">
						<h2 id="load-time">-</h2>
						Import Time
					</div>
				</div>
				<div class="col-md-4 col-xs-12">
					<div class="checkbox">
						<label><input type="checkbox" id="tags" checked value="">Hide tag import messages</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<?php

		$start = microtime( true );
		require( "classes/import.php" );

		$import = new Import;

		$import->getJsonFile( "people.json" );
		$time_elapsed_secs = microtime( true ) - $start;

		echo "<div id='seconds' class='hidden'>" . number_format( $time_elapsed_secs, 2 ) . "</div>";
		?>
	</div>
</div>
