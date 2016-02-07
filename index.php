<?php
// Include libraries
include('txty.api.php');

// Start Txty object
$txty = new Txty('mike', 'WPBFMD3PM5DVHSPDTP4B2DDQRU26NLFGHHVB4NQDATGRZVUG8EHZ74XP');



// If post
if($_POST)
{
	if(ctype_digit($_POST['dialcode']) && ctype_digit($_POST['number']) && $_POST['name'] && is_array($_POST['groups']))
	{
		$contact = $txty->createContact($_POST['name'], $_POST['dialcode'] . $_POST['number'], $_POST['groups']);
		if($contact)
		{
			$alert = '<div class="alert alert-success" role="alert">Awesome! Everything went well, and you are now a subsriber, welcome on the team! :-)</div>';
		} else {
			$alert = '<div class="alert alert-danger" role="alert">Damn! Something went wrong in some weird way, can you contact us? Awesome, talk to you soon!</div>';
		}
	}
}



// Dialcodes
$dialcodes = $txty->getDialcodes();
if($dialcodes)
{
	foreach($dialcodes as $value)
		$dialcodesHTML.= '<option value="' . $value['dialcode'] . '" iso="' . strtolower($value['cciso']) . '"' . (!$contact && $_POST['dialcode'] == $value['dialcode'] ? ' SELECTED' : false) . '>' . $value['country'] . '</option>';
}

// Groups
$groups = $txty->viewGroups();
if($groups['groups'])
{
	foreach($groups['groups'] as $value)
		$groupsHTML.= '<label class="btn btn-primary"><input type="checkbox" name="groups[]" value="' . $value['controlgroup'] . '">&nbsp;' . $value['name'] . '</label>';
}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<title>Event Signup</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://raunsbaekdk.github.io/flags/flags16.css" rel="stylesheet">
	</head>
	<body>
		<div class="jumbotron">
			<div class="container">
				<h1>Event Signup</h1>
				<p>Signup to Our Awesome Event below. We will send you relevant and informative notifications from time to time. Nothing much to say, welcome on board! :-)</p>
			</div>
		</div>
		<div class="container">
			<?php echo $alert; ?>
			<form class="form-horizontal" role="form" method="POST" action="index.php">
				<div class="form-group">
					<label for="dialcode" class="col-sm-2 control-label">Country</label>
					<div class="col-sm-4">
						<div class="input-group">
							<span id="dialcodeImg" class="input-group-addon hidden"><span></span></span>
							<select class="form-control" id="dialcode" name="dialcode">
								<option>Select country</option>
								<?php echo $dialcodesHTML; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="number" class="col-sm-2 control-label">Number</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="number" name="number" placeholder="Your mobile number" value="<?php echo (!$contact ? $_POST['number'] : false); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label">Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="name" name="name" placeholder="Your full name" value="<?php echo (!$contact ? $_POST['name'] : false); ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label">Groups</label>
					<div class="col-sm-4">
						<div class="btn-group" data-toggle="buttons">
							<?php echo $groupsHTML; ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-success">Sign up</button>
					</div>
				</div>
			</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript">
		var dialcode;
		function isNumeric(n)
		{
			return !isNaN(parseFloat(n)) && isFinite(n);
		}

		jQuery(document).ready(function()
		{
			jQuery("#dialcode").change(function()
			{
				// Set dialcode
				dialcode = jQuery(this).val();

				// Change flag
				var flag = jQuery(this).find("option:selected").attr("iso");
				jQuery("#dialcodeImg span").removeClass();
				jQuery("#dialcodeImg span").addClass('flag ' + flag);
				jQuery("#dialcodeImg").removeClass('hidden');
			});

			jQuery("#number").bind('focusout keydown keypress keyup', function ()
			{
				if(!isNumeric(jQuery("#number").val()))
					jQuery("#number").val(jQuery("#number").val().replace(/\D/g, ''));
			});
		});
		</script>
	</body>
</html>