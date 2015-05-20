<?php 
	/***
	 * login.php
	 * Author: raf
	 * @20150518
	 * This file is for the user login view
	 */
?>
<!DOCTYPE php>
<html>
	<head>
		<meta charset="ISO-8859-1">
		<title>Calculator Login</title>
		<style>
			#mainContent {
				background-color:#330000;
				border-radius:30px;
				position:absolute;
				left:36%;
				top:25%;
				width:400px;
			}
			
			body {
				font-family:"Segoe UI";
				color:white;
				background-color:#000;
			}
			
			table {
				background-color:#330000;
				margin:auto;
				padding:30px;
			}
			
			#ok, th, input {
				border:none;
				text-decoration:none;
				background-color:red;
				font-family:"Segoe UI";
				color:white;
				border-radius:30px;
				padding:10px;
				font-size:1.5em;
			}
			
			input[type="submit"] {
				padding:10px 30px;
				margin:auto;
			}
			
			.center {
				text-align:center;
			}
			
			#invalid {
				display:inline-block;
				width:100%;
			}
			
			th {
				display:block;
				color:white;
				font-size:2em;
				margin:10px 0;
				padding:10px 5px;
				border-radius:30px;
			}
			
			#ok {
				width:100px;
			}
			
			#prompt {
				background-color:#330000;
				color:white;
				position:absolute;
				top:25%;
				left:40%;
				right:40%;
				bottom:40%;
				text-align:center;
				padding:20px;
			}
		</style>
		<script src="jquery-2.1.1.js"></script>
		<script>
			$(document).ready(function() {
				<?php 
					if( !$_SESSION['faillogin'] ) {
						echo '$("#prompt").hide();';
					}
				?>

				/**
				* checks if username is registered
				*/
				$("#luname").on('input',function() {
					if( $("#luname").val().length > 0 ) {
						$.ajax({
							url : "controller.php",
							method : "POST",
							data : {
								'request' : 'username',
								'uname' : $("#luname").val()
							},
							success : function(a) {
								a = a != 'true';
								$("#exists").text( a ? "Username is not regis" 
													+ "tered" : "" );
							} 
						});
					} else {
						$("#exists").text("");
					}
				});

				/**
				* checks and displays any persistent errors
				*/
				$("form#login").submit(function() {
					var errors = $("#exists").text();

					if( errors.length != 0 ) {
						$("#promptContent").html(errors);
						$("#prompt").show(500);
						return false;
					} else {
						return true;
					}
				});

				/**
				* hides prompt box
				*/
				$("#ok").click(function() {
					$("#prompt").hide(500);
				});
			});
		</script>
	</head>
	<body>
		<div id="prompt">
			<div id="promptContent"><?php echo ($_SESSION['faillogin'] ? "Incorrect Password" : ""); ?></div><br /><br />
			<button id="ok" class="goldButton">Ok</button>
		</div>
		<div id="mainContent" class="mainRegister">
			<form id="login" action="/Calculator/" method="post">
				<table>
					<tr><th>Calculator</th></tr>
					<tr><td>
						<?php
							$value = "";
							
							//if wrong password
							if( $_SESSION['faillogin'] ) {
								$value = 'value="'.$_POST['uname'].'"';
							}
						?>
						<input type="text" class="input" placeholder="Username" name="uname" id="luname" <?php echo $value; ?> required />
					</td></tr>
					<tr><td>
						<span id="exists"></span>
					</td></tr>
					<tr><td>
						<input type="password" class="input" placeholder="Password" name="pass" id="lpass" required />
					</td></tr>
					<tr><td>
						<span id="invalid" class="center">
							<?php echo ($_SESSION['faillogin'] ? "Incorrect Password" : ""); ?>
						</span>
					</td></tr>
					<tr><td class="center">
						<input type="submit" value="Login" id="login" class="goldButton" />
					</td></tr>
				</table>
			</form>
		</div>
	</body>
</html>